package andro.heklaton.rsc.util.stepcounter;

import com.orangegangsters.github.lib.BootCompletedReceiver;
import com.orangegangsters.github.lib.SensorStepServiceManager;

/**
 * Created by Andro on 11/21/2015.
 */
public class BootCompletedReceiverImpl extends BootCompletedReceiver {

    private SensorStepServiceManager mSensorManager;

    @Override
    public SensorStepServiceManager getSensorManagerImpl() {
        if(mSensorManager == null) {
            mSensorManager = new SensorStepServiceManagerImpl();
        }
        return mSensorManager;
    }
}