package andro.heklaton.rsc.ui.activity;

import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Color;
import android.graphics.drawable.Drawable;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.location.Location;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.nfc.NdefMessage;
import android.nfc.NdefRecord;
import android.nfc.NfcAdapter;
import android.nfc.Tag;
import android.nfc.tech.Ndef;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Parcelable;
import android.os.Vibrator;
import android.speech.RecognizerIntent;
import android.speech.SpeechRecognizer;
import android.support.v7.app.AlertDialog;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

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
import andro.heklaton.rsc.api.request.LocationSendRequest;
import andro.heklaton.rsc.api.request.PlayerDeadRequest;
import andro.heklaton.rsc.model.gamestatus.GameStatus;
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
 * Created by Andro on 11/21/2015.
 */
public class MapboxActivity extends VoiceControlActivity implements SensorEventListener {

    public static final String COLOR_GREEN = "#00FF00";
    public static final String COLOR_RED = "#FF0000";

    private List<MarkerOptions> markers;
    private List<PolylineOptions> zones;
    private SpriteFactory spriteFactory;
    private TextView tvTimer;

    private Drawable ally;
    private Drawable enemy;
    private Drawable allyDead;
    private Drawable attention;
    private Drawable help;

    private Timer timer;
    private Timer timer2;

    private NfcAdapter mNfcAdapter;
    private Tag detectedTag;
    private PendingIntent pendingIntent;
    private IntentFilter[] readTagFilters;

    private List<PolygonOptions> takenZones;

    private PlayerStatus player;

    boolean activityActive;
    private SensorManager mSensorManager;
    private Sensor mSensor;
    private int endTime;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        getPlayerStatus();
        mSensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        mSensor = mSensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);

        tvTimer = (TextView) findViewById(R.id.timer);

        mSpeechRecognizer = SpeechRecognizer.createSpeechRecognizer(this);
        mSpeechRecognizerIntent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        mSpeechRecognizerIntent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,
                RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        mSpeechRecognizerIntent.putExtra(RecognizerIntent.EXTRA_CALLING_PACKAGE,
                this.getPackageName());

        mSpeechRecognizer.setRecognitionListener(this);

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
        readTagFilters = new IntentFilter[]{tagDetected,filter2};

        ally = getResources().getDrawable(R.drawable.ally);
        enemy = getResources().getDrawable(R.drawable.enemy);
        allyDead = getResources().getDrawable(R.drawable.dead_ally);
        attention = getResources().getDrawable(R.drawable.attention_ally);
        help = getResources().getDrawable(R.drawable.help_ally);

        spriteFactory = new SpriteFactory(mapView);

        markers = new ArrayList<>();

        zones = MapsUtil.getZones();
        takenZones = MapsUtil.getPolygonZones();

        startSendingLocation();
        startReceivingStats();

        mapView.setOnMapLongClickListener(new MapView.OnMapLongClickListener() {
            @Override
            public void onMapLongClick(LatLng point) {
                sendFire(point.getLatitude(), point.getLongitude());
            }
        });

        mapView.setOnMapClickListener(new MapView.OnMapClickListener() {
            @Override
            public void onMapClick(LatLng point) {
                sendWarning(point.getLatitude(), point.getLongitude());
            }
        });

        Button btnDead = (Button) findViewById(R.id.btn_dead);
        btnDead.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                markPlayerDead();

                if (mSensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER) != null) {
                    mSensor = mSensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);
                    mSensorManager.registerListener(MapboxActivity.this, mSensor, SensorManager.SENSOR_DELAY_NORMAL);
                } else {
                    Toast.makeText(MapboxActivity.this, "No accelerometer", Toast.LENGTH_SHORT).show();
                }

                AlertDialog.Builder builder = new AlertDialog.Builder(MapboxActivity.this);
                LayoutInflater inflater = getLayoutInflater();
                View view = inflater.inflate(R.layout.dialog_image, null);
                builder.setView(view);
                builder.show();
            }
        });

        ImageButton btnVoiceControl = (ImageButton) findViewById(R.id.voice);
        btnVoiceControl.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (!listening) {
                    listening = true;
                    mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                }
            }
        });

        RestHelper.getRestApi().getStatus(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                new Callback<GameStatus>() {
                    @Override
                    public void success(GameStatus gameStatus, Response response) {
                        endTime = gameStatus.getData().getEndTimeStamp();
                        setTime();
                    }

                    @Override
                    public void failure(RetrofitError error) {

                    }
                }
        );

    }

    void setTime() {
        new CountDownTimer(endTime - (System.currentTimeMillis()), 1000) {

            public void onTick(long millisUntilFinished) {
                Log.d("TIME", "end: " + endTime + " - " + "current: " + System.currentTimeMillis());
                int seconds = (int) (millisUntilFinished / 1000) % 60 ;
                int minutes = (int) ((millisUntilFinished / (1000*60)) % 60);
                tvTimer.setText(minutes + ":" + seconds);
            }

            public void onFinish() {
                tvTimer.setText("done!");
            }
        }.start();
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

    /**
     * Send location every second
     */
    private void startSendingLocation() {
        Location location = mapView.getMyLocation();
        if (location != null) {

            // prepare request
            final LocationSendRequest request = new LocationSendRequest();
            request.setGame(1);
            request.setLat(location.getLatitude());
            request.setLng(location.getLongitude());

            RestHelper.getRestApi().sendCurrentLocation(
                    RestAPI.HEADER,
                    PrefsHelper.getToken(MapboxActivity.this),
                    request,
                    new Callback<BaseResponse>() {
                        @Override
                        public void success(BaseResponse baseResponse, Response response) {
                            //Log.d("Status", baseResponse.getStatus());
                            //Log.d("Message", baseResponse.getMessage());
                        }

                        @Override
                        public void failure(RetrofitError error) {
                            //Log.d("Error", error.getMessage());
                        }
                    }
            );
        }
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
                                    updateStats(stats.getData().getStats());
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

    private void updateStats(final List<Stat> stats) {
        markers.clear();
        if (mapView != null) {
            mapView.removeAllAnnotations();
        }

        redrawPolygons();

        if (player != null) {
            if (player.getData() != null) {
                for (Stat s : stats) {
                    if (s.getIsLive() && s.getTeam().equals(player.getData().getTeam())) {
                        MarkerOptions marker = new MarkerOptions();
                        marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
                        marker.icon(spriteFactory.fromDrawable(ally));
                        markers.add(marker);
                    }
                    if (!s.getIsLive() && s.getTeam().equals(player.getData().getTeam())) {
                        MarkerOptions marker = new MarkerOptions();
                        marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
                        marker.icon(spriteFactory.fromDrawable(allyDead));
                        markers.add(marker);
                    }
                }

            }

            mapView.addMarkers(markers);
        }
    }

    private void updateZones(Game game) {
        if (player != null) {
            if (game.getOwnerRegion1() != null) {
                if (game.getOwnerRegion1().getId().equals(player.getData().getTeam())) {
                    markZone(1, COLOR_GREEN);
                } else {
                    markZone(1, COLOR_RED);
                }
            }

            if (game.getOwnerRegion2() != null) {
                if (game.getOwnerRegion2().getId().equals(player.getData().getTeam())) {
                    markZone(2, COLOR_GREEN);
                } else {
                    markZone(2, COLOR_RED);
                }
            }

            if (game.getOwnerRegion3() != null) {
                if (game.getOwnerRegion3().getId().equals(player.getData().getTeam())) {
                    markZone(3, COLOR_GREEN);
                } else {
                    markZone(3, COLOR_RED);
                }
            }

            if (game.getOwnerRegion4() != null) {
                if (game.getOwnerRegion4().getId().equals(player.getData().getTeam())) {
                    markZone(4, COLOR_GREEN);
                } else {
                    markZone(4, COLOR_RED);
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

    public void readFromTag(Intent intent){
        Ndef ndef = Ndef.get(detectedTag);
        try{
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
        }
        catch (Exception e) {
            Toast.makeText(getApplicationContext(), "Cannot Read From Tag.", Toast.LENGTH_LONG).show();
        }
    }

    protected void onNewIntent(Intent intent) {
        setIntent(intent);
        if(getIntent().getAction().equals(NfcAdapter.ACTION_TAG_DISCOVERED)){
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

    private void markPlayerDead() {
        PlayerDeadRequest request = new PlayerDeadRequest();
        request.setIsLive(0);
        if (player.getData() != null) {
            request.setPlayerId(player.getData().getId());

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
    public void onPause()  {
        super.onPause();
        mapView.onPause();
        if (mNfcAdapter != null) {
            mNfcAdapter.disableForegroundDispatch(this);
        }
        unregisterReceiver(mMessageReceiver);
    }

    @Override
    public void onResume() {
        super.onResume();
        mapView.onResume();
        if (mNfcAdapter != null) {
            mNfcAdapter.enableForegroundDispatch(this, pendingIntent, readTagFilters, null);
        }
        registerReceiver(mMessageReceiver, new IntentFilter("GCM"));
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
        mSensorManager.unregisterListener(this);
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        mapView.onSaveInstanceState(outState);
    }

    @Override
    public void onSensorChanged(SensorEvent event) {
        Log.d("Accelerometer", String.valueOf(event.values[2]));
        if (event.values[2] < 0 && event.values[2] > -7) {

            Intent intent = new Intent(this, JoinGameActivity.class);
            startActivity(intent);
        }
    }

    @Override
    public void onAccuracyChanged(Sensor sensor, int accuracy) {

    }

    private BroadcastReceiver mMessageReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {

            // Extract data included in the Intent
            String message = intent.getStringExtra("message");
            Log.d("GCM MESSAGE", message);

            if (message.equals(COMMAND_ATTENTION)) {
                mapView.addMarker(new MarkerOptions().position(
                        new LatLng(intent.getDoubleExtra("lat", 0), intent.getDoubleExtra("lng", 0))
                ).icon(spriteFactory.fromDrawable(attention)));
                playNotificationSound();
            } else if (message.equals(COMMAND_HELP)) {
                mapView.addMarker(new MarkerOptions().position(
                        new LatLng(intent.getDoubleExtra("lat", 0), intent.getDoubleExtra("lng", 0))
                ).icon(spriteFactory.fromDrawable(help)));
                playNotificationSound();
            }
        }
    };

    private void playNotificationSound() {
        try {
            Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
            Ringtone r = RingtoneManager.getRingtone(getApplicationContext(), notification);
            r.play();
            Vibrator v = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
            v.vibrate(500);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}
