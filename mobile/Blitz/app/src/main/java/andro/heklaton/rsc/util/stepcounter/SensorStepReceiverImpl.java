package andro.heklaton.rsc.util.stepcounter;

import com.orangegangsters.github.lib.SensorStepReceiver;

/**
 * Created by Andro on 11/21/2015.
 */
public class SensorStepReceiverImpl extends SensorStepReceiver {

    @Override
    public Class getServiceClass() {
        return SensorStepServiceImpl.class;
    }

}