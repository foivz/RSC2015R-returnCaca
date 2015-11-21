package andro.heklaton.rsc.ui.activity;

import android.graphics.Color;
import android.graphics.drawable.Drawable;
import android.location.Location;
import android.os.Bundle;
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.Toolbar;
import android.util.Log;

import com.mapbox.mapboxsdk.annotations.Annotation;
import com.mapbox.mapboxsdk.annotations.MarkerOptions;
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

        ally = getResources().getDrawable(R.drawable.ally);
        enemy = getResources().getDrawable(R.drawable.enemy);

        spriteFactory = new SpriteFactory(mapView);

        markers = new ArrayList<>();

        zones = MapsUtil.getZones();

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
