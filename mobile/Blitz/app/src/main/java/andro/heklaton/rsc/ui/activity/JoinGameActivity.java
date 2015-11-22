package andro.heklaton.rsc.ui.activity;

import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.api.RestAPI;
import andro.heklaton.rsc.api.RestHelper;
import andro.heklaton.rsc.model.location.BaseResponse;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import andro.heklaton.rsc.util.PrefsHelper;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Andro on 11/22/2015.
 */
public class JoinGameActivity extends DrawerActivity {

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);

        Button btnJoinGame = (Button) findViewById(R.id.join_game);
        btnJoinGame.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                RestHelper.getRestApi().joinQueue(
                        RestAPI.HEADER,
                        PrefsHelper.getToken(JoinGameActivity.this),
                        "",
                        new Callback<BaseResponse>() {
                            @Override
                            public void success(BaseResponse baseResponse, Response response) {
                                if (baseResponse.getStatus().equals("ok")) {
                                    Toast.makeText(JoinGameActivity.this, R.string.joined, Toast.LENGTH_SHORT).show();
                                } else {
                                    Log.d("Queue", baseResponse.getMessage());
                                }
                            }

                            @Override
                            public void failure(RetrofitError error) {
                                if (error.getMessage() != null) {
                                    Log.d("Queue", error.getMessage());
                                }
                            }
                        }
                );
            }
        });
    }

    @Override
    protected Toolbar getToolbar() {
        return (Toolbar) findViewById(R.id.toolbar);
    }

    @Override
    public int getLayoutId() {
        return R.layout.activity_join_game;
    }

    @Override
    public int getNavigationItemPosition() {
        return 0;
    }

}
