package andro.heklaton.rsc.ui.activity;

import android.graphics.Color;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;

import com.mapbox.mapboxsdk.annotations.PolylineOptions;
import com.mapbox.mapboxsdk.constants.Style;
import com.mapbox.mapboxsdk.geometry.LatLng;
import com.mapbox.mapboxsdk.views.MapView;

import java.util.List;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import andro.heklaton.rsc.ui.util.MapsUtil;

/**
 * Created by Andro on 11/21/2015.
 */
public class MapboxActivity extends DrawerActivity {

    private MapView mapView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mapView = (MapView) findViewById(R.id.mapview);
        mapView.setStyleUrl(Style.DARK);
        mapView.setCenterCoordinate(new LatLng(46.306390, 16.339145));
        mapView.setZoomLevel(16.8);
        mapView.onCreate(savedInstanceState);

        List<PolylineOptions> zones = MapsUtil.getZones();
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
