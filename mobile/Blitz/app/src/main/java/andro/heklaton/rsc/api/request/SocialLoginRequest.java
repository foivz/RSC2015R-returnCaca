package andro.heklaton.rsc.api.request;

import com.google.gson.annotations.Expose;

/**
 * Created by Andro on 11/21/2015.
 */
public class SocialLoginRequest {

    @Expose
    private String username;
    @Expose
    private String password;
    @Expose
    private String email;

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }
}
