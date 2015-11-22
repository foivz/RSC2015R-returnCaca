package andro.heklaton.rsc.api.request;

import com.google.gson.annotations.Expose;

/**
 * Created by Andro on 11/22/2015.
 */
public class PlayerDeadRequest {

    @Expose
    private int isLive;
    @Expose
    private int playerId;


    public int getIsLive() {
        return isLive;
    }

    public void setIsLive(int isLive) {
        this.isLive = isLive;
    }

    public int getPlayerId() {
        return playerId;
    }

    public void setPlayerId(int playerId) {
        this.playerId = playerId;
    }
}
