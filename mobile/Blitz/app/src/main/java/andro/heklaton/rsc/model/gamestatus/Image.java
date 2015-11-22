
package andro.heklaton.rsc.model.gamestatus;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Image {

    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("imageName")
    @Expose
    private String imageName;

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
     *     The imageName
     */
    public String getImageName() {
        return imageName;
    }

    /**
     * 
     * @param imageName
     *     The imageName
     */
    public void setImageName(String imageName) {
        this.imageName = imageName;
    }

}
