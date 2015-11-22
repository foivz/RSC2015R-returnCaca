
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Team1_ {

    @SerializedName("total")
    @Expose
    private Integer total;
    @SerializedName("dead")
    @Expose
    private Integer dead;
    @SerializedName("alive")
    @Expose
    private Integer alive;

    /**
     * 
     * @return
     *     The total
     */
    public Integer getTotal() {
        return total;
    }

    /**
     * 
     * @param total
     *     The total
     */
    public void setTotal(Integer total) {
        this.total = total;
    }

    /**
     * 
     * @return
     *     The dead
     */
    public Integer getDead() {
        return dead;
    }

    /**
     * 
     * @param dead
     *     The dead
     */
    public void setDead(Integer dead) {
        this.dead = dead;
    }

    /**
     * 
     * @return
     *     The alive
     */
    public Integer getAlive() {
        return alive;
    }

    /**
     * 
     * @param alive
     *     The alive
     */
    public void setAlive(Integer alive) {
        this.alive = alive;
    }

}
