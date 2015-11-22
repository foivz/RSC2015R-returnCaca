package andro.heklaton.rsc.api;

import andro.heklaton.rsc.api.request.CaptureRequest;
import andro.heklaton.rsc.api.request.LocationSendRequest;
import andro.heklaton.rsc.api.request.LoginRequest;
import andro.heklaton.rsc.api.request.MessageRequest;
import andro.heklaton.rsc.api.request.PlayerDeadRequest;
import andro.heklaton.rsc.api.request.SocialLoginRequest;
import andro.heklaton.rsc.model.location.BaseResponse;
import andro.heklaton.rsc.model.login.User;
import andro.heklaton.rsc.model.player.PlayerStatus;
import andro.heklaton.rsc.model.stats.Stats;
import retrofit.Callback;
import retrofit.http.Body;
import retrofit.http.GET;
import retrofit.http.Header;
import retrofit.http.POST;

/**
 * Created by Andro on 11/19/2015.
 */
public interface RestAPI {

    String HEADER="application/json";

    @POST("/login")
    void login(
            @Header("Content-Type") String contentType,
            @Body LoginRequest loginRequest,
            Callback<User> response
    );

    @POST("/login")
    void login(
            @Header("Content-Type") String contentType,
            @Body SocialLoginRequest socialLoginRequest,
            Callback<User> response
    );

    @POST("/locations")
    void sendCurrentLocation(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            @Body LocationSendRequest request,
            Callback<BaseResponse> response
    );

    @GET("/stats/1")
    void getCurrentStats(
            @Header("Content-Type") String contentType,
            Callback<Stats> response
    );

    @POST("/capture-flag")
    void captureFlag(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            @Body CaptureRequest request,
            Callback<BaseResponse> response
    );

    @GET("/player-status")
    void getPlayerStatus(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            Callback<PlayerStatus> response
    );

    @POST("/msg")
    void sendMessage(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            @Body MessageRequest request,
            Callback<BaseResponse> response
    );

    @POST("/stats")
    void markPlayerDead(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            @Body PlayerDeadRequest request,
            Callback<BaseResponse> response
    );

    @POST("/queue/join")
    void joinQueue(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            @Body String body,
            Callback<BaseResponse> request
    );

}
