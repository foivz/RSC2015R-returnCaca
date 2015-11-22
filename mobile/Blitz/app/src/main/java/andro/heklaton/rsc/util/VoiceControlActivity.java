package andro.heklaton.rsc.util;

import android.content.Intent;
import android.location.Location;
import android.os.Bundle;
import android.os.Handler;
import android.speech.RecognitionListener;
import android.speech.SpeechRecognizer;
import android.support.v4.view.GestureDetectorCompat;
import android.util.Log;
import android.view.GestureDetector;
import android.view.MotionEvent;
import android.view.View;
import android.widget.FrameLayout;

import com.mapbox.mapboxsdk.views.MapView;

import java.util.ArrayList;

import andro.heklaton.rsc.R;
import andro.heklaton.rsc.api.RestAPI;
import andro.heklaton.rsc.api.RestHelper;
import andro.heklaton.rsc.api.request.MessageRequest;
import andro.heklaton.rsc.model.location.LocationSendResponse;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Andro on 11/21/2015.
 */
public abstract class VoiceControlActivity extends DrawerActivity implements RecognitionListener, GestureDetector.OnGestureListener, GestureDetector.OnDoubleTapListener {

    public static final String COMMAND_FIRE = "ATTENTION";
    public static final String COMMAND_HELP = "HELP";
    public static final String TAG = "Voice";

    protected SpeechRecognizer mSpeechRecognizer;
    protected Intent mSpeechRecognizerIntent;
    protected MapView mapView;

    public VoiceControlActivity() {
    }

    @Override
    public void onBeginningOfSpeech() {
        Log.d(TAG, "onBeginingOfSpeech");
    }

    @Override
    public void onBufferReceived(byte[] buffer) {

    }

    @Override
    public void onEndOfSpeech() {
        Log.d(TAG, "onEndOfSpeech");
    }

    @Override
    public void onError(int error) {
        Thread thread = new Thread() {
            @Override
            public void run() {
                try {
                    synchronized(this){
                        wait(1000);
                    }
                }
                catch(InterruptedException ex){
                }
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                    }
                });
            }
        };

        thread.start();
    }

    @Override
    public void onEvent(int eventType, Bundle params) {

    }

    @Override public void onPartialResults(Bundle partialResults)
    {

    }

    @Override
    public void onReadyForSpeech(Bundle params) {
        Log.d("tag", "onReadyForSpeech"); //$NON-NLS-1$
    }

    @Override
    public void onResults(Bundle results) {
        Log.d(TAG, "onResults"); //$NON-NLS-1$
        ArrayList<String> matches = results.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION);

        boolean matchesFire = false;
        boolean matchesHelp = false;

        if (matches != null) {
            for (String s : matches) {
                Log.d("Voice", s);
                if (s.equalsIgnoreCase(COMMAND_FIRE)) {
                    matchesFire = true;
                }
                if (s.equalsIgnoreCase(COMMAND_HELP)) {
                    matchesHelp = true;
                }
            }
        }

        Log.d("Voice command", "Fire");

        if (matchesFire) {
            sendFire();
        } else if (matchesHelp) {
            sendHelp();
        }
    }

    private void sendFire() {
        Log.d("Voice command", "Fire");
        final Location location = mapView.getMyLocation();

        MessageRequest request = new MessageRequest();
        request.setMessage(COMMAND_FIRE);

        if (location != null) {
            request.setLat(location.getLatitude());
            request.setLng(location.getLongitude());
        }

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<LocationSendResponse>() {
                    @Override
                    public void success(LocationSendResponse locationSendResponse, Response response) {
                        Log.d("Voice command", locationSendResponse.getStatus());
                        mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Voice command", error.getMessage());
                        }
                    }
                }
        );
    }

    private void sendHelp() {
        Log.d("Voice command", "Help");
        final Location location = mapView.getMyLocation();

        MessageRequest request = new MessageRequest();
        request.setMessage(COMMAND_HELP);

        if (location != null) {
            request.setLat(location.getLatitude());
            request.setLng(location.getLongitude());
        }

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<LocationSendResponse>() {
                    @Override
                    public void success(LocationSendResponse locationSendResponse, Response response) {
                        Log.d("Voice command", locationSendResponse.getStatus());
                        mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Voice command", error.getMessage());
                        }
                    }
                }
        );
    }

    protected void sendFire(Double lat, Double lng) {
        Log.d("Voice command", "Help");

        MessageRequest request = new MessageRequest();
        request.setMessage(COMMAND_HELP);

        request.setLat(lat);
        request.setLng(lng);

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<LocationSendResponse>() {
                    @Override
                    public void success(LocationSendResponse locationSendResponse, Response response) {
                        Log.d("Voice command", locationSendResponse.getStatus());
                        mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Voice command", error.getMessage());
                        }
                    }
                }
        );
    }

    protected void sendWarning(Double lat, Double lng) {
        Log.d("Voice command", "Warning");

        MessageRequest request = new MessageRequest();
        request.setMessage(COMMAND_FIRE);

        request.setLat(lat);
        request.setLng(lng);

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<LocationSendResponse>() {
                    @Override
                    public void success(LocationSendResponse locationSendResponse, Response response) {
                        Log.d("Voice command", locationSendResponse.getStatus());
                        mSpeechRecognizer.startListening(mSpeechRecognizerIntent);
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getMessage() != null) {
                            Log.d("Voice command", error.getMessage());
                        }
                    }
                }
        );
    }

    protected GestureDetectorCompat mDetector;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mDetector = new GestureDetectorCompat(this, this);
        mDetector.setOnDoubleTapListener(this);
    }

    @Override
    public void onRmsChanged(float rmsdB) {
    }

    @Override
    public boolean onSingleTapConfirmed(MotionEvent e) {
        Log.d("single tap", "confirmed");
        return false;
    }

    @Override
    public boolean onDoubleTap(MotionEvent e) {
        Log.d("double tap", "confirmed");
        return false;
    }

    @Override
    public boolean onDoubleTapEvent(MotionEvent e) {
        Log.d("double tap event", "confirmed");
        return false;
    }

    @Override
    public boolean onDown(MotionEvent e) {
        return false;
    }

    @Override
    public void onShowPress(MotionEvent e) {

    }

    @Override
    public boolean onSingleTapUp(MotionEvent e) {
        return false;
    }

    @Override
    public boolean onScroll(MotionEvent e1, MotionEvent e2, float distanceX, float distanceY) {
        return false;
    }

    @Override
    public void onLongPress(MotionEvent e) {

    }

    @Override
    public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {
        return false;
    }
}