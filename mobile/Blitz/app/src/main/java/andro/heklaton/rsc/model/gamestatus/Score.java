
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Score {

    @SerializedName("team1")
    @Expose
    private Integer team1;
    @SerializedName("team2")
    @Expose
    private Integer team2;

    /**
     * 
     * @return
     *     The team1
     */
    public Integer getTeam1() {
        return team1;
    }

    /**
     * 
     * @param team1
     *     The team1
     */
    public void setTeam1(Integer team1) {
        this.team1 = team1;
    }

    /**
     * 
     * @return
     *     The team2
     */
    public Integer getTeam2() {
        return team2;
    }

    /**
     * 
     * @param team2
     *     The team2
     */
    public void setTeam2(Integer team2) {
        this.team2 = team2;
    }

}
