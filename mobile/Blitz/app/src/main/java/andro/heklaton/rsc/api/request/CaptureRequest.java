package andro.heklaton.rsc.api.request;

import com.google.gson.annotations.Expose;

/**
 * Created by Andro on 11/22/2015.
 */
public class CaptureRequest {

    @Expose
    private Integer regionId;

    public int getRegionId() {
        return regionId;
    }

    public void setRegionId(Integer regionId) {
        this.regionId = regionId;
    }
}
