
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Odds {

    @SerializedName("team1")
    @Expose
    private Double team1;
    @SerializedName("team2")
    @Expose
    private Double team2;

    /**
     * 
     * @return
     *     The team1
     */
    public Double getTeam1() {
        return team1;
    }

    /**
     * 
     * @param team1
     *     The team1
     */
    public void setTeam1(Double team1) {
        this.team1 = team1;
    }

    /**
     * 
     * @return
     *     The team2
     */
    public Double getTeam2() {
        return team2;
    }

    /**
     * 
     * @param team2
     *     The team2
     */
    public void setTeam2(Double team2) {
        this.team2 = team2;
    }

}
