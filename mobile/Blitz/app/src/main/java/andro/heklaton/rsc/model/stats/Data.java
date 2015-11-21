
package andro.heklaton.rsc.model.stats;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Data {

    @SerializedName("stats")
    @Expose
    private List<Stat> stats = new ArrayList<Stat>();
    @SerializedName("game")
    @Expose
    private Game game;

    /**
     * 
     * @return
     *     The stats
     */
    public List<Stat> getStats() {
        return stats;
    }

    /**
     * 
     * @param stats
     *     The stats
     */
    public void setStats(List<Stat> stats) {
        this.stats = stats;
    }

    /**
     * 
     * @return
     *     The game
     */
    public Game getGame() {
        return game;
    }

    /**
     * 
     * @param game
     *     The game
     */
    public void setGame(Game game) {
        this.game = game;
    }

}
