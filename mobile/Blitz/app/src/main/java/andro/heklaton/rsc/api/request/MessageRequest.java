package andro.heklaton.rsc.api.request;

import com.google.gson.annotations.Expose;

/**
 * Created by Andro on 11/22/2015.
 */
public class MessageRequest {

    @Expose
    private String message;
    @Expose
    private Double lat;
    @Expose
    private Double lng;

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Double getLat() {
        return lat;
    }

    public void setLat(Double lat) {
        this.lat = lat;
    }

    public Double getLng() {
        return lng;
    }

    public void setLng(Double lng) {
        this.lng = lng;
    }
}
