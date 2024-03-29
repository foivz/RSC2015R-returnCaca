package andro.heklaton.rsc.ui.activity;

import android.app.PendingIntent;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Color;
import android.graphics.drawable.Drawable;
import android.location.Location;
import android.nfc.NdefMessage;
import android.nfc.NdefRecord;
import android.nfc.NfcAdapter;
import android.nfc.Tag;
import android.nfc.tech.Ndef;
import android.os.Bundle;
import android.os.Parcelable;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import com.mapbox.mapboxsdk.annotations.Marker;
import com.mapbox.mapboxsdk.annotations.MarkerOptions;
import com.mapbox.mapboxsdk.annotations.PolygonOptions;
import com.mapbox.mapboxsdk.annotations.PolylineOptions;
import com.mapbox.mapboxsdk.annotations.SpriteFactory;
import com.mapbox.mapboxsdk.constants.Style;
import com.mapbox.mapboxsdk.geometry.LatLng;
import com.mapbox.mapboxsdk.views.MapView;

import java.util.ArrayList;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.api.RestAPI;
import andro.heklaton.rsc.api.RestHelper;
import andro.heklaton.rsc.api.request.CaptureRequest;
import andro.heklaton.rsc.api.request.MessageRequest;
import andro.heklaton.rsc.api.request.PlayerDeadRequest;
import andro.heklaton.rsc.model.location.BaseResponse;
import andro.heklaton.rsc.model.player.PlayerStatus;
import andro.heklaton.rsc.model.stats.Game;
import andro.heklaton.rsc.model.stats.Stat;
import andro.heklaton.rsc.model.stats.Stats;
import andro.heklaton.rsc.ui.util.MapsUtil;
import andro.heklaton.rsc.util.PrefsHelper;
import andro.heklaton.rsc.util.VoiceControlActivity;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Andro on 11/22/2015.
 */
public class MapboxJudgeActivity extends VoiceControlActivity {

    public static final String COLOR_GREEN = "#00FF00";
    public static final String COLOR_RED = "#FF0000";

    private List<MarkerOptions> markers;
    private List<PolylineOptions> zones;
    private SpriteFactory spriteFactory;

    private Drawable ally;
    private Drawable enemy;
    private Drawable allyDead;
    private Drawable enemyDead;

    private Timer timer;
    private Timer timer2;

    private NfcAdapter mNfcAdapter;
    private Tag detectedTag;
    private PendingIntent pendingIntent;
    private IntentFilter[] readTagFilters;

    private List<PolygonOptions> takenZones;

    private PlayerStatus player;

    boolean activityActive;
    private List<Stat> statses;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        getPlayerStatus();

        mapView = (MapView) findViewById(R.id.mapview);
        mapView.setStyleUrl(Style.DARK);
        mapView.setCenterCoordinate(new LatLng(46.306390, 16.339145));
        mapView.setZoomLevel(16.8);
        mapView.onCreate(savedInstanceState);
        mapView.setMyLocationEnabled(true);
        mapView.setCompassEnabled(true);

        timer = new Timer();
        timer2 = new Timer();

        mNfcAdapter = NfcAdapter.getDefaultAdapter(this);
        detectedTag = getIntent().getParcelableExtra(NfcAdapter.EXTRA_TAG);
        pendingIntent = PendingIntent.getActivity(getApplicationContext(), 0,
                new Intent(this, getClass()).addFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP), 0);
        IntentFilter tagDetected = new IntentFilter(NfcAdapter.ACTION_TAG_DISCOVERED);
        IntentFilter filter2 = new IntentFilter(NfcAdapter.ACTION_NDEF_DISCOVERED);
        readTagFilters = new IntentFilter[]{tagDetected, filter2};

        ally = getResources().getDrawable(R.drawable.ally_large);
        enemy = getResources().getDrawable(R.drawable.enemy_large);
        allyDead = getResources().getDrawable(R.drawable.dead_ally);
        enemyDead = getResources().getDrawable(R.drawable.dead_enemy);

        spriteFactory = new SpriteFactory(mapView);

        markers = new ArrayList<>();

        zones = MapsUtil.getZones();
        takenZones = MapsUtil.getPolygonZones();

        startReceivingStats();

        mapView.setOnMarkerClickListener(new MapView.OnMarkerClickListener() {
            @Override
            public boolean onMarkerClick(Marker marker) {
                markPlayerDead(Integer.valueOf(marker.getTitle()));
                return true;
            }
        });

        mapView.setOnMapLongClickListener(new MapView.OnMapLongClickListener() {
            @Override
            public void onMapLongClick(LatLng point) {
                sendAbort();
            }
        });

        Button btnDead = (Button) findViewById(R.id.btn_dead);
        btnDead.setVisibility(View.GONE);
    }

    private void getPlayerStatus() {
        RestHelper.getRestApi().getPlayerStatus(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                new Callback<PlayerStatus>() {
                    @Override
                    public void success(PlayerStatus playerStatus, Response response) {
                        if (activityActive) {
                            player = playerStatus;
                        }
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Error player", error.getMessage());
                        }
                    }
                }
        );
    }

    private void startReceivingStats() {
        timer.schedule(new TimerTask() {
            @Override
            public void run() {
                RestHelper.getRestApi().getCurrentStats(
                        RestAPI.HEADER,
                        new Callback<Stats>() {
                            @Override
                            public void success(Stats stats, Response response) {
                                Log.d("Success", response.getReason());
                                if (activityActive) {
                                    statses = stats.getData().getStats();
                                    updateStats(stats.getData().getStats(), stats.getData().getGame());
                                    updateZones(stats.getData().getGame());
                                }
                            }

                            @Override
                            public void failure(RetrofitError error) {
                                if (error.getMessage() != null) {
                                    //Log.d("Stats fail", error.getMessage());
                                }
                            }
                        });
            }
        }, 1000, 1000);
    }

    private void updateStats(final List<Stat> stats, Game game) {
        markers.clear();
        if (mapView != null) {
            mapView.removeAllAnnotations();
        }

        redrawPolygons();

        for (Stat s : stats) {
            if (s.getIsLive() && s.getTeam().equals(game.getTeam1().getId())) {
                MarkerOptions marker = new MarkerOptions();
                marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
                marker.icon(spriteFactory.fromDrawable(ally));
                marker.title(String.valueOf(s.getPlayer().getId()));
                markers.add(marker);
            }
            if (s.getIsLive() && s.getTeam().equals(game.getTeam2().getId())) {
                MarkerOptions marker = new MarkerOptions();
                marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
                marker.icon(spriteFactory.fromDrawable(enemy));
                marker.title(String.valueOf(s.getPlayer().getId()));
                markers.add(marker);
            }
            if (!s.getIsLive() && s.getTeam().equals(game.getTeam1().getId())) {
                MarkerOptions marker = new MarkerOptions();
                marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
                marker.icon(spriteFactory.fromDrawable(allyDead));
                marker.title(String.valueOf(s.getPlayer().getId()));
                markers.add(marker);
            }
            if (!s.getIsLive() && s.getTeam().equals(game.getTeam2().getId())) {
                MarkerOptions marker = new MarkerOptions();
                marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
                marker.icon(spriteFactory.fromDrawable(enemyDead));
                marker.title(String.valueOf(s.getPlayer().getId()));
                markers.add(marker);
            }
        }



        mapView.addMarkers(markers);
    }

    private void updateZones(Game game) {
        if (player != null) {
            if (game.getOwnerRegion1() != null) {
                if (player.getData() != null) {
                    if (game.getOwnerRegion1().getId().equals(player.getData().getTeam())) {
                        markZone(1, COLOR_GREEN);
                    } else {
                        markZone(1, COLOR_RED);
                    }
                }
            }

            if (game.getOwnerRegion2() != null) {
                if (player.getData() != null) {
                    if (game.getOwnerRegion2().getId().equals(player.getData().getTeam())) {
                        markZone(2, COLOR_GREEN);
                    } else {
                        markZone(2, COLOR_RED);
                    }
                }
            }

            if (game.getOwnerRegion3() != null) {
                if (player.getData() != null) {
                    if (game.getOwnerRegion3().getId().equals(player.getData().getTeam())) {
                        markZone(3, COLOR_GREEN);
                    } else {
                        markZone(3, COLOR_RED);
                    }
                }
            }

            if (game.getOwnerRegion4() != null) {
                if (player.getData() != null) {
                    if (game.getOwnerRegion4().getId().equals(player.getData().getTeam())) {
                        markZone(4, COLOR_GREEN);
                    } else {
                        markZone(4, COLOR_RED);
                    }
                }
            }
        }
    }

    private void redrawPolygons() {
        for (int i = 0; i < zones.size(); i++) {
            PolylineOptions po = zones.get(i);
            po.width(4);

            switch (i) {
                case 0:
                    po.color(Color.parseColor("#FF0000"));
                    break;
                case 1:
                    po.color(Color.parseColor("#0077FF"));
                    break;
                case 2:
                    po.color(Color.parseColor("#FFDD00"));
                    break;
                case 3:
                    po.color(Color.parseColor("#9900FF"));
            }

            mapView.addPolyline(po);
        }
    }

    public void readFromTag(Intent intent) {
        Ndef ndef = Ndef.get(detectedTag);
        try {
            ndef.connect();

            Parcelable[] messages = intent.getParcelableArrayExtra(NfcAdapter.EXTRA_NDEF_MESSAGES);

            if (messages != null) {
                NdefMessage[] ndefMessages = new NdefMessage[messages.length];
                for (int i = 0; i < messages.length; i++) {
                    ndefMessages[i] = (NdefMessage) messages[i];
                }
                NdefRecord record = ndefMessages[0].getRecords()[0];

                byte[] payload = record.getPayload();
                String text = new String(payload);
                String[] tag = text.split("-");
                String zone = tag[1];
                Toast.makeText(getApplicationContext(), zone, Toast.LENGTH_LONG).show();
                markZone(Integer.valueOf(zone), "#00FF00");
                markZoneOnline(Integer.valueOf(zone));

                ndef.close();
            }
        } catch (Exception e) {
            Toast.makeText(getApplicationContext(), "Cannot Read From Tag.", Toast.LENGTH_LONG).show();
        }
    }

    protected void onNewIntent(Intent intent) {
        setIntent(intent);
        if (getIntent().getAction().equals(NfcAdapter.ACTION_TAG_DISCOVERED)) {
            detectedTag = intent.getParcelableExtra(NfcAdapter.EXTRA_TAG);

            readFromTag(getIntent());
        }
    }

    private void markZone(int zone, String color) {
        PolygonOptions po = takenZones.get(zone - 1);
        po.fillColor(Color.parseColor(color));
        po.alpha(0.5f);
        mapView.addPolygon(takenZones.get(zone - 1));
    }

    private void markZoneOnline(int zone) {
        CaptureRequest request = new CaptureRequest();
        request.setRegionId(zone);

        RestHelper.getRestApi().captureFlag(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<BaseResponse>() {
                    @Override
                    public void success(BaseResponse baseResponse, Response response) {
                        Log.d("Capture", baseResponse.getMessage());
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Capture", error.getMessage());
                        }
                    }
                }
        );
    }

    private void markPlayerDead(int id) {
        PlayerDeadRequest request = new PlayerDeadRequest();

        boolean alive = false;
        for (Stat s : statses) {
            if (s.getPlayer().getId().equals(id)) {
                alive = s.getPlayer().getIsLive();
            }
        }

        if (alive) {
            request.setIsLive(0);
        } else {
            request.setIsLive(1);
        }

        request.setPlayerId(id);

        RestHelper.getRestApi().markPlayerDead(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<BaseResponse>() {
                    @Override
                    public void success(BaseResponse baseResponse, Response response) {

                    }

                    @Override
                    public void failure(RetrofitError error) {

                    }
                }
        );
    }

    private void sendAbort() {
        Log.d("Voice command", "Fire");
        final Location location = mapView.getMyLocation();

        MessageRequest request = new MessageRequest();
        request.setMessage(COMMAND_ABORT);

        if (location != null) {
            request.setLat(location.getLatitude());
            request.setLng(location.getLongitude());
        }

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<BaseResponse>() {
                    @Override
                    public void success(BaseResponse baseResponse, Response response) {
                        Log.d("Voice command", baseResponse.getStatus());
                        mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Voice command", error.getMessage());
                        }
                    }
                }
        );
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        getSupportActionBar().hide();
    }

    @Override
    protected Toolbar getToolbar() {
        return (Toolbar) findViewById(R.id.toolbar);
    }

    @Override
    public int getLayoutId() {
        return R.layout.activity_mapbox;
    }

    @Override
    public int getNavigationItemPosition() {
        return 1;
    }

    @Override
    protected void onStart() {
        super.onStart();
        mapView.onStart();
        activityActive = true;
    }

    @Override
    protected void onStop() {
        super.onStop();
        mapView.onStop();
        activityActive = false;
    }

    @Override
    public void onPause() {
        super.onPause();
        mapView.onPause();
    }

    @Override
    public void onResume() {
        super.onResume();
        mapView.onResume();
        mNfcAdapter.enableForegroundDispatch(this, pendingIntent, readTagFilters, null);
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        mapView.onDestroy();
        timer.cancel();
        timer2.cancel();
        if (mSpeechRecognizer != null) {
            mSpeechRecognizer.destroy();
        }

    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        mapView.onSaveInstanceState(outState);
    }

}