
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Player {

    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("alias")
    @Expose
    private String alias;
    @SerializedName("level")
    @Expose
    private Object level;
    @SerializedName("team")
    @Expose
    private Integer team;
    @SerializedName("isLive")
    @Expose
    private Boolean isLive;
    @SerializedName("image")
    @Expose
    private Image image;

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
     *     The alias
     */
    public String getAlias() {
        return alias;
    }

    /**
     * 
     * @param alias
     *     The alias
     */
    public void setAlias(String alias) {
        this.alias = alias;
    }

    /**
     * 
     * @return
     *     The level
     */
    public Object getLevel() {
        return level;
    }

    /**
     * 
     * @param level
     *     The level
     */
    public void setLevel(Object level) {
        this.level = level;
    }

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
     *     The isLive
     */
    public Boolean getIsLive() {
        return isLive;
    }

    /**
     * 
     * @param isLive
     *     The isLive
     */
    public void setIsLive(Boolean isLive) {
        this.isLive = isLive;
    }

    /**
     * 
     * @return
     *     The image
     */
    public Image getImage() {
        return image;
    }

    /**
     * 
     * @param image
     *     The image
     */
    public void setImage(Image image) {
        this.image = image;
    }

}
