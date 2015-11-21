package andro.heklaton.rsc.api;

import andro.heklaton.rsc.util.Constants;
import retrofit.RestAdapter;

/**
 * Created by Andro on 11/21/2015.
 */
public class RestHelper {

    public static RestAPI getRestApi() {
        RestAdapter adapter = new RestAdapter.Builder()
                .setEndpoint(Constants.ENDPOINT)
                .build();

        return adapter.create(RestAPI.class);
    }

}
