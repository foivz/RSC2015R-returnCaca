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
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.widget.Toast;

import com.mapbox.mapboxsdk.annotations.Annotation;
import com.mapbox.mapboxsdk.annotations.MarkerOptions;
import com.mapbox.mapboxsdk.annotations.PolygonOptions;
import com.mapbox.mapboxsdk.annotations.PolylineOptions;
import com.mapbox.mapboxsdk.annotations.Sprite;
import com.mapbox.mapboxsdk.annotations.SpriteFactory;
import com.mapbox.mapboxsdk.constants.MyLocationTracking;
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
import andro.heklaton.rsc.api.request.LocationSendRequest;
import andro.heklaton.rsc.model.location.LocationSendResponse;
import andro.heklaton.rsc.model.stats.Stat;
import andro.heklaton.rsc.model.stats.Stats;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import andro.heklaton.rsc.ui.util.ImageUtil;
import andro.heklaton.rsc.ui.util.MapsUtil;
import andro.heklaton.rsc.util.PrefsHelper;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Andro on 11/21/2015.
 */
public class MapboxActivity extends DrawerActivity {

    private MapView mapView;
    private List<MarkerOptions> markers;
    private List<PolylineOptions> zones;
    private SpriteFactory spriteFactory;

    private Drawable ally;
    private Drawable enemy;
    private Timer timer;
    private Timer timer2;

    private NfcAdapter mNfcAdapter;
    private Tag detectedTag;
    private PendingIntent pendingIntent;
    private IntentFilter[] readTagFilters;

    private List<PolygonOptions> takenZones;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
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

        spriteFactory = new SpriteFactory(mapView);

        markers = new ArrayList<>();

        zones = MapsUtil.getZones();
        takenZones = MapsUtil.getPolygonZones();

        startSendingLocation();
        startReceivingStats();
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

            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    RestHelper.getRestApi().sendCurrentLocation(
                            RestAPI.HEADER,
                            PrefsHelper.getToken(MapboxActivity.this),
                            request,
                            new Callback<LocationSendResponse>() {
                                @Override
                                public void success(LocationSendResponse locationSendResponse, Response response) {
                                    Log.d("Status", locationSendResponse.getStatus());
                                    Log.d("Message", locationSendResponse.getMessage());
                                }

                                @Override
                                public void failure(RetrofitError error) {
                                    //Log.d("Error", error.getMessage());
                                }
                            }
                    );
                }
            });
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
                                updateStats(stats.getData().getStats());
                            }

                            @Override
                            public void failure(RetrofitError error) {
                            }
                        });
            }
        }, 1000, 1000);
    }

    private void updateStats(final List<Stat> stats) {
        markers.clear();
        mapView.removeAllAnnotations();

        redrawPolygons();

        for (Stat s : stats) {
            MarkerOptions marker = new MarkerOptions();
            marker.position(new LatLng(Double.valueOf(s.getLocation().getLat()), Double.valueOf(s.getLocation().getLng())));
            markers.add(marker);
        }

        mapView.addMarkers(markers);
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
                markZone(Integer.valueOf(zone));

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

    private void markZone(int zone) {
        PolygonOptions po = takenZones.get(zone);
        po.fillColor(Color.parseColor("#00bb00"));
        po.alpha(0.5f);
        mapView.addPolygon(takenZones.get(zone));
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
    }

    @Override
    protected void onStop() {
        super.onStop();
        mapView.onStop();
    }

    @Override
    public void onPause()  {
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
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        mapView.onSaveInstanceState(outState);
    }

}
