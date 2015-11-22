
package andro.heklaton.rsc.model.stats;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Game {

    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("team1")
    @Expose
    private Team1 team1;
    @SerializedName("team2")
    @Expose
    private Team2 team2;
    @SerializedName("winner")
    @Expose
    private Object winner;
    @SerializedName("ownerRegion1")
    @Expose
    private Team ownerRegion1;
    @SerializedName("ownerRegion2")
    @Expose
    private Team ownerRegion2;
    @SerializedName("ownerRegion3")
    @Expose
    private Team ownerRegion3;
    @SerializedName("ownerRegion4")
    @Expose
    private Team ownerRegion4;

    /**
     * 
     * @return
     *     The id
     */
    public Integer getId() {
        return id;
    }

    /**
     * 
     * @param id
     *     The id
     */
    public void setId(Integer id) {
        this.id = id;
    }

    /**
     * 
     * @return
     *     The team1
     */
    public Team1 getTeam1() {
        return team1;
    }

    /**
     * 
     * @param team1
     *     The team1
     */
    public void setTeam1(Team1 team1) {
        this.team1 = team1;
    }

    /**
     * 
     * @return
     *     The team2
     */
    public Team2 getTeam2() {
        return team2;
    }

    /**
     * 
     * @param team2
     *     The team2
     */
    public void setTeam2(Team2 team2) {
        this.team2 = team2;
    }

    /**
     * 
     * @return
     *     The winner
     */
    public Object getWinner() {
        return winner;
    }

    /**
     * 
     * @param winner
     *     The winner
     */
    public void setWinner(Object winner) {
        this.winner = winner;
    }

    /**
     * 
     * @return
     *     The ownerRegion1
     */
    public Team getOwnerRegion1() {
        return ownerRegion1;
    }

    /**
     * 
     * @param ownerRegion1
     *     The ownerRegion1
     */
    public void setOwnerRegion1(Team ownerRegion1) {
        this.ownerRegion1 = ownerRegion1;
    }

    /**
     * 
     * @return
     *     The ownerRegion2
     */
    public Team getOwnerRegion2() {
        return ownerRegion2;
    }

    /**
     * 
     * @param ownerRegion2
     *     The ownerRegion2
     */
    public void setOwnerRegion2(Team ownerRegion2) {
        this.ownerRegion2 = ownerRegion2;
    }

    /**
     * 
     * @return
     *     The ownerRegion3
     */
    public Team getOwnerRegion3() {
        return ownerRegion3;
    }

    /**
     * 
     * @param ownerRegion3
     *     The ownerRegion3
     */
    public void setOwnerRegion3(Team ownerRegion3) {
        this.ownerRegion3 = ownerRegion3;
    }

    /**
     * 
     * @return
     *     The ownerRegion4
     */
    public Team getOwnerRegion4() {
        return ownerRegion4;
    }

    /**
     * 
     * @param ownerRegion4
     *     The ownerRegion4
     */
    public void setOwnerRegion4(Team ownerRegion4) {
        this.ownerRegion4 = ownerRegion4;
    }

}
