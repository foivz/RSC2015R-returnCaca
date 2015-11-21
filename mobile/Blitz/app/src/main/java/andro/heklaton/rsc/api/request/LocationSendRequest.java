package andro.heklaton.rsc.api.request;

import com.google.gson.annotations.Expose;

public class LocationSendRequest {

    @Expose
    private Double lat;
    @Expose
    private Double lng;
    @Expose
    private Integer game;

    /**
     *
     * @return
     * The lat
     */
    public Double getLat() {
        return lat;
    }

    /**
     *
     * @param lat
     * The lat
     */
    public void setLat(Double lat) {
        this.lat = lat;
    }

    /**
     *
     * @return
     * The lng
     */
    public Double getLng() {
        return lng;
    }

    /**
     *
     * @param lng
     * The lng
     */
    public void setLng(Double lng) {
        this.lng = lng;
    }

    /**
     *
     * @return
     * The game
     */
    public Integer getGame() {
        return game;
    }

    /**
     *
     * @param game
     * The game
     */
    public void setGame(Integer game) {
        this.game = game;
    }

}