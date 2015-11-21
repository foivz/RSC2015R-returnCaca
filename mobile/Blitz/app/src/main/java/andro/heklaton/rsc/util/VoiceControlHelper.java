package andro.heklaton.rsc.util;

import android.os.Bundle;
import android.speech.RecognitionListener;
import android.speech.SpeechRecognizer;
import android.util.Log;

import java.util.ArrayList;

/**
 * Created by Andro on 11/21/2015.
 */
public class VoiceControlHelper implements RecognitionListener {

    public interface OnVoiceControlEnd {
        void onVoiceControlEnd(String text);
    }

    OnVoiceControlEnd onVoiceControlEnd;

    public VoiceControlHelper(OnVoiceControlEnd onVoiceControlEnd) {
        this.onVoiceControlEnd = onVoiceControlEnd;
    }

    @Override
    public void onBeginningOfSpeech() {
        //Log.d(TAG, "onBeginingOfSpeech");
    }

    @Override
    public void onBufferReceived(byte[] buffer) {

    }

    @Override
    public void onEndOfSpeech() {
        //Log.d(TAG, "onEndOfSpeech");
    }

    @Override
    public void onError(int error) {
        //Log.d(TAG, "error = " + error);
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
        //Log.d(TAG, "onResults"); //$NON-NLS-1$
        ArrayList<String> matches = results.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION);
        for (String s : matches) {
            Log.d("Voice", s);
        }

        onVoiceControlEnd.onVoiceControlEnd(matches.get(0));

        // matches are the return values of speech recognition engine
        // Use these values for whatever you wish to do
    }

    @Override
    public void onRmsChanged(float rmsdB) {
    }



}