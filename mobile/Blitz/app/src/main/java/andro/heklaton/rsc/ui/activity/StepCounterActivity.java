package andro.heklaton.rsc.ui.activity;

import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.widget.TextView;

import com.orangegangsters.github.lib.SensorStepCallback;
import com.orangegangsters.github.lib.SensorStepService;
import com.orangegangsters.github.lib.SensorStepServiceManager;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import andro.heklaton.rsc.util.stepcounter.SensorStepServiceImpl;

/**
 * Created by Andro on 11/21/2015.
 */
public class StepCounterActivity extends DrawerActivity implements SensorStepCallback {

    private SensorStepServiceImpl mSensorService;
    private TextView tvSteps;

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);

        SensorStepService.setCallback(this);
        mSensorService = new SensorStepServiceImpl(this);
        SensorStepServiceManager.startAutoUpdate(this);

        tvSteps = (TextView) findViewById(R.id.tv_steps);
    }

    @Override
    protected Toolbar getToolbar() {
        return (Toolbar) findViewById(R.id.toolbar);
    }

    @Override
    public int getLayoutId() {
        return R.layout.activity_step_counter;
    }

    @Override
    public int getNavigationItemPosition() {
        return 2;
    }

    @Override
    public void onUpdateSteps(int steps) {
        Log.d("step", "step");
        tvSteps.setText(mSensorService.getSteps());
    }
}
