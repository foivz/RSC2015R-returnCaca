package andro.heklaton.rsc.util;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

public class PrefsHelper {

    public static final String KEY_USERNAME = "username";
    public static final String KEY_EMAIL = "email";
    public static final String KEY_TOKEN = "token";
    public static final String KEY_GCM_SENT = "gcm_sent";
    public static final String KEY_GCM_TOKEN = "gcm_token";

    public static SharedPreferences getSharedPreferences(Context context) {
        return PreferenceManager.getDefaultSharedPreferences(context);
    }

    public static void saveUsername(Context context, String username) {
        getSharedPreferences(context)
                .edit()
                .putString(KEY_USERNAME, username)
                .apply();
    }

    public static String getUsername(Context context) {
        return getSharedPreferences(context)
                .getString(KEY_USERNAME, null);
    }

    public static void saveEmail(Context context, String email) {
        getSharedPreferences(context)
                .edit()
                .putString(KEY_EMAIL, email)
                .apply();
    }

    public static String getEmail(Context context) {
        return getSharedPreferences(context)
                .getString(KEY_EMAIL, null);
    }

    public static void saveToken(Context context, String token) {
        getSharedPreferences(context)
                .edit()
                .putString(KEY_TOKEN, token)
                .apply();
    }

    public static String getToken(Context context) {
        return getSharedPreferences(context)
                .getString(KEY_TOKEN, null);
    }

    public static void setGcmTokenSent(Context context, boolean isSet) {
        getSharedPreferences(context)
                .edit()
                .putBoolean(KEY_GCM_SENT, isSet)
                .apply();
    }

    public static void saveGcmToken(Context context, String token) {
        getSharedPreferences(context)
                .edit()
                .putString(KEY_GCM_TOKEN, token)
                .apply();
    }

    public static String getGcmToken(Context context) {
        return getSharedPreferences(context)
                .getString(KEY_GCM_TOKEN, "");
    }

}
