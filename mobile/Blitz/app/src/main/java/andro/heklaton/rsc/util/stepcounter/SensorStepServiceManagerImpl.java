package andro.heklaton.rsc.util.stepcounter;

import com.orangegangsters.github.lib.SensorStepServiceManager;

/**
 * Created by Andro on 11/21/2015.
 */
public class SensorStepServiceManagerImpl extends SensorStepServiceManager {

    @Override
    public Class getReceiverClass() {
        return SensorStepReceiverImpl.class;
    }

}