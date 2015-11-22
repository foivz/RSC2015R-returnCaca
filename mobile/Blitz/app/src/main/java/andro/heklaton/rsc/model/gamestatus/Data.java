
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Data {

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
    private Object ownerRegion1;
    @SerializedName("ownerRegion2")
    @Expose
    private Object ownerRegion2;
    @SerializedName("ownerRegion3")
    @Expose
    private Object ownerRegion3;
    @SerializedName("ownerRegion4")
    @Expose
    private Object ownerRegion4;
    @SerializedName("regions")
    @Expose
    private Regions regions;
    @SerializedName("regionCount")
    @Expose
    private RegionCount regionCount;
    @SerializedName("playerCount")
    @Expose
    private PlayerCount playerCount;
    @SerializedName("score")
    @Expose
    private Score score;
    @SerializedName("endTimeStamp")
    @Expose
    private Integer endTimeStamp;
    @SerializedName("odds")
    @Expose
    private Odds odds;
    @SerializedName("duration")
    @Expose
    private Integer duration;
    @SerializedName("killPoints")
    @Expose
    private Integer killPoints;
    @SerializedName("flagPoints")
    @Expose
    private Integer flagPoints;
    @SerializedName("isGameActive")
    @Expose
    private Boolean isGameActive;

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
    public Object getOwnerRegion1() {
        return ownerRegion1;
    }

    /**
     * 
     * @param ownerRegion1
     *     The ownerRegion1
     */
    public void setOwnerRegion1(Object ownerRegion1) {
        this.ownerRegion1 = ownerRegion1;
    }

    /**
     * 
     * @return
     *     The ownerRegion2
     */
    public Object getOwnerRegion2() {
        return ownerRegion2;
    }

    /**
     * 
     * @param ownerRegion2
     *     The ownerRegion2
     */
    public void setOwnerRegion2(Object ownerRegion2) {
        this.ownerRegion2 = ownerRegion2;
    }

    /**
     * 
     * @return
     *     The ownerRegion3
     */
    public Object getOwnerRegion3() {
        return ownerRegion3;
    }

    /**
     * 
     * @param ownerRegion3
     *     The ownerRegion3
     */
    public void setOwnerRegion3(Object ownerRegion3) {
        this.ownerRegion3 = ownerRegion3;
    }

    /**
     * 
     * @return
     *     The ownerRegion4
     */
    public Object getOwnerRegion4() {
        return ownerRegion4;
    }

    /**
     * 
     * @param ownerRegion4
     *     The ownerRegion4
     */
    public void setOwnerRegion4(Object ownerRegion4) {
        this.ownerRegion4 = ownerRegion4;
    }

    /**
     * 
     * @return
     *     The regions
     */
    public Regions getRegions() {
        return regions;
    }

    /**
     * 
     * @param regions
     *     The regions
     */
    public void setRegions(Regions regions) {
        this.regions = regions;
    }

    /**
     * 
     * @return
     *     The regionCount
     */
    public RegionCount getRegionCount() {
        return regionCount;
    }

    /**
     * 
     * @param regionCount
     *     The regionCount
     */
    public void setRegionCount(RegionCount regionCount) {
        this.regionCount = regionCount;
    }

    /**
     * 
     * @return
     *     The playerCount
     */
    public PlayerCount getPlayerCount() {
        return playerCount;
    }

    /**
     * 
     * @param playerCount
     *     The playerCount
     */
    public void setPlayerCount(PlayerCount playerCount) {
        this.playerCount = playerCount;
    }

    /**
     * 
     * @return
     *     The score
     */
    public Score getScore() {
        return score;
    }

    /**
     * 
     * @param score
     *     The score
     */
    public void setScore(Score score) {
        this.score = score;
    }

    /**
     * 
     * @return
     *     The endTimeStamp
     */
    public Integer getEndTimeStamp() {
        return endTimeStamp;
    }

    /**
     * 
     * @param endTimeStamp
     *     The endTimeStamp
     */
    public void setEndTimeStamp(Integer endTimeStamp) {
        this.endTimeStamp = endTimeStamp;
    }

    /**
     * 
     * @return
     *     The odds
     */
    public Odds getOdds() {
        return odds;
    }

    /**
     * 
     * @param odds
     *     The odds
     */
    public void setOdds(Odds odds) {
        this.odds = odds;
    }

    /**
     * 
     * @return
     *     The duration
     */
    public Integer getDuration() {
        return duration;
    }

    /**
     * 
     * @param duration
     *     The duration
     */
    public void setDuration(Integer duration) {
        this.duration = duration;
    }

    /**
     * 
     * @return
     *     The killPoints
     */
    public Integer getKillPoints() {
        return killPoints;
    }

    /**
     * 
     * @param killPoints
     *     The killPoints
     */
    public void setKillPoints(Integer killPoints) {
        this.killPoints = killPoints;
    }

    /**
     * 
     * @return
     *     The flagPoints
     */
    public Integer getFlagPoints() {
        return flagPoints;
    }

    /**
     * 
     * @param flagPoints
     *     The flagPoints
     */
    public void setFlagPoints(Integer flagPoints) {
        this.flagPoints = flagPoints;
    }

    /**
     * 
     * @return
     *     The isGameActive
     */
    public Boolean getIsGameActive() {
        return isGameActive;
    }

    /**
     * 
     * @param isGameActive
     *     The isGameActive
     */
    public void setIsGameActive(Boolean isGameActive) {
        this.isGameActive = isGameActive;
    }

}
