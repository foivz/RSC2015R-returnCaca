package andro.heklaton.rsc.gcm;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import com.google.android.gms.gcm.GcmListenerService;

public class MyGcmListenerService extends GcmListenerService {

    private static final String TAG = "MyGcmListenerService";

    /**
     * Called when message is received.
     *
     * @param from SenderID of the sender.
     * @param data Data bundle containing message data as key/value pairs.
     *             For Set of keys use data.keySet().
     */
    // [START receive_message]
    @Override
    public void onMessageReceived(String from, Bundle data) {
        String message = data.getString("message");
        Log.d(TAG, "From: " + from);
        Log.d(TAG, "Message: " + message);
        Log.d("Lat", data.getString("lat"));
        Log.d("Lat", data.getString("lng"));

        if (from.startsWith("/topics/")) {
            // message received from some topic.
        } else {
            // normal downstream message.
        }

        // [START_EXCLUDE]
        /**
         * Production applications would usually process the message here.
         * Eg: - Syncing with server.
         *     - Store message in local database.
         *     - Update UI.
         */

        /**
         * In some cases it may be useful to show a notification indicating to the user
         * that a message was received.
         */
        updateMyActivity(getApplicationContext() ,message, data.getString("lat"), data.getString("lng"));
        // [END_EXCLUDE]
    }
    // [END receive_message]


    static void updateMyActivity(Context context, String message, String lat, String lng) {

        Intent intent = new Intent("GCM");

        //put whatever data you want to send, if any
        intent.putExtra("message", message);
        intent.putExtra("lat", Double.valueOf(lat));
        intent.putExtra("lng", Double.valueOf(lng));

        //send broadcast
        context.sendBroadcast(intent);
    }

}