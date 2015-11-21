
package andro.heklaton.rsc.model.stats;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Stat {

    @SerializedName("team")
    @Expose
    private Integer team;
    @SerializedName("player")
    @Expose
    private Player player;
    @SerializedName("isLive")
    @Expose
    private Integer isLive;
    @SerializedName("location")
    @Expose
    private Location location;

    /**
     * 
     * @return
     *     The team
     */
    public Integer getTeam() {
        return team;
    }

    /**
     * 
     * @param team
     *     The team
     */
    public void setTeam(Integer team) {
        this.team = team;
    }

    /**
     * 
     * @return
     *     The player
     */
    public Player getPlayer() {
        return player;
    }

    /**
     * 
     * @param player
     *     The player
     */
    public void setPlayer(Player player) {
        this.player = player;
    }

    /**
     * 
     * @return
     *     The isLive
     */
    public Integer getIsLive() {
        return isLive;
    }

    /**
     * 
     * @param isLive
     *     The isLive
     */
    public void setIsLive(Integer isLive) {
        this.isLive = isLive;
    }

    /**
     * 
     * @return
     *     The location
     */
    public Location getLocation() {
        return location;
    }

    /**
     * 
     * @param location
     *     The location
     */
    public void setLocation(Location location) {
        this.location = location;
    }

}
