package andro.heklaton.rsc.ui.activity;

import android.os.Bundle;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.ui.activity.base.BaseSplash;
import andro.heklaton.rsc.util.PrefsHelper;

public class SplashActivity extends BaseSplash {

    @Override
    public int provideLayoutRes() {
        return R.layout.activity_splash;
    }

    @Override
    public int getSplashTime() {
        return 1000;
    }

    @Override
    public Class getNextClassActivity() {
        if (PrefsHelper.getUsername(this) != null) {
            return MainActivity.class;
        } else {
            return LoginActivity.class;
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }
}
