
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class PlayerCount {

    @SerializedName("team1")
    @Expose
    private Team1_ team1;
    @SerializedName("team2")
    @Expose
    private Team2_ team2;

    /**
     * 
     * @return
     *     The team1
     */
    public Team1_ getTeam1() {
        return team1;
    }

    /**
     * 
     * @param team1
     *     The team1
     */
    public void setTeam1(Team1_ team1) {
        this.team1 = team1;
    }

    /**
     * 
     * @return
     *     The team2
     */
    public Team2_ getTeam2() {
        return team2;
    }

    /**
     * 
     * @param team2
     *     The team2
     */
    public void setTeam2(Team2_ team2) {
        this.team2 = team2;
    }

}
