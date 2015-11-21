package andro.heklaton.rsc.api;

import andro.heklaton.rsc.api.request.LocationSendRequest;
import andro.heklaton.rsc.api.request.LoginRequest;
import andro.heklaton.rsc.api.request.SocialLoginRequest;
import andro.heklaton.rsc.model.location.LocationSendResponse;
import andro.heklaton.rsc.model.login.User;
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

    @GET("/positions")
    void getPositions(
            @Header("Content-Type") String contentType
            );

    @POST("/locations")
    void sendCurrentLocation(
            @Header("Content-Type") String contentType,
            @Header("X-Api-Token") String token,
            @Body LocationSendRequest request,
            Callback<LocationSendResponse> response
    );

    @GET("/stats/1")
    void getCurrentStats(
            @Header("Content-Type") String contentType,
            Callback<Stats> response
    );

}
