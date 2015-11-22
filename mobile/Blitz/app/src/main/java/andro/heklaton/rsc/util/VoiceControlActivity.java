package andro.heklaton.rsc.util;

import android.content.Intent;
import android.os.Bundle;
import android.speech.RecognitionListener;
import android.speech.SpeechRecognizer;
import android.support.v4.view.GestureDetectorCompat;
import android.util.Log;
import android.view.GestureDetector;
import android.view.MotionEvent;

import com.mapbox.mapboxsdk.geometry.LatLng;
import com.mapbox.mapboxsdk.views.MapView;

import java.util.ArrayList;

import andro.heklaton.rsc.api.RestAPI;
import andro.heklaton.rsc.api.RestHelper;
import andro.heklaton.rsc.api.request.MessageRequest;
import andro.heklaton.rsc.model.location.BaseResponse;
import andro.heklaton.rsc.ui.activity.base.DrawerActivity;
import andro.heklaton.rsc.ui.util.MapsUtil;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Andro on 11/21/2015.
 */
public abstract class VoiceControlActivity extends DrawerActivity implements RecognitionListener, GestureDetector.OnGestureListener, GestureDetector.OnDoubleTapListener {

    public static final String COMMAND_ATTENTION = "ATTENTION";
    public static final String COMMAND_HELP = "HELP";
    public static final String COMMAND_ABORT = "ABORT";
    public static final String TAG = "Voice";

    protected SpeechRecognizer mSpeechRecognizer;
    protected Intent mSpeechRecognizerIntent;
    protected MapView mapView;

    protected boolean listening = false;

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
        listening = false;
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
        listening = false;
        Log.d(TAG, "onResults"); //$NON-NLS-1$
        ArrayList<String> matches = results.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION);

        boolean matchesFire = false;
        boolean matchesHelp = false;

        if (matches != null) {
            for (String s : matches) {
                Log.d("Voice", s);
                if (s.equalsIgnoreCase(COMMAND_ATTENTION)) {
                    matchesFire = true;
                }
                if (s.equalsIgnoreCase(COMMAND_HELP)) {
                    matchesHelp = true;
                }
            }
        }

        Log.d("Voice command", "Fire");
        LatLng latLng = MapsUtil.getRandomLocation();

        if (matchesFire) {
            sendFire(latLng.getLatitude(), latLng.getLongitude());
        } else if (matchesHelp) {
            sendWarning(latLng.getLatitude(), latLng.getLongitude());
        }
    }


    protected void sendFire(Double lat, Double lng) {
        Log.d("Voice command", "Help");

        MessageRequest request = new MessageRequest();
        request.setMessage(COMMAND_ATTENTION);

        request.setLat(lat);
        request.setLng(lng);

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<BaseResponse>() {
                    @Override
                    public void success(BaseResponse baseResponse, Response response) {
                        Log.d("Voice command", baseResponse.getStatus());
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
        request.setMessage(COMMAND_HELP);

        request.setLat(lat);
        request.setLng(lng);

        RestHelper.getRestApi().sendMessage(
                RestAPI.HEADER,
                PrefsHelper.getToken(this),
                request,
                new Callback<BaseResponse>() {
                    @Override
                    public void success(BaseResponse baseResponse, Response response) {
                        Log.d("Voice command", baseResponse.getStatus());
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