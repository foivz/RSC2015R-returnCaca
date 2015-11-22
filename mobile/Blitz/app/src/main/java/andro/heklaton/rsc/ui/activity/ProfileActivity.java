package andro.heklaton.rsc.ui.activity;

import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.webkit.WebView;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.api.RestAPI;
import andro.heklaton.rsc.api.RestHelper;
import andro.heklaton.rsc.model.player.PlayerStatus;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import andro.heklaton.rsc.util.PrefsHelper;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Andro on 11/22/2015.
 */
public class ProfileActivity extends DrawerActivity {

    public static final String URL = "http://www.heklaton.xyz/mobile/user?id=";
    WebView webView;

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);

        RestHelper.getRestApi().getPlayerStatus(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                new Callback<PlayerStatus>() {
                    @Override
                    public void success(PlayerStatus playerStatus, Response response) {
                        openWebProfile(playerStatus.getData().getId());
                    }

                    @Override
                    public void failure(RetrofitError error) {

                    }
                }
        );

        webView = (WebView) findViewById(R.id.webView);

    }

    private void openWebProfile(int id) {
        webView.loadUrl(URL + id);
    }

    @Override
    protected Toolbar getToolbar() {
        return (Toolbar) findViewById(R.id.toolbar);
    }

    @Override
    public int getLayoutId() {
        return R.layout.activity_profile;
    }

    @Override
    public int getNavigationItemPosition() {
        return 2;
    }

}
