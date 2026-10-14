package com.nimienterprise.app;

import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.GradientDrawable;
import android.net.ConnectivityManager;
import android.net.Network;
import android.net.NetworkCapabilities;
import android.net.NetworkRequest;
import android.os.Bundle;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.getcapacitor.BridgeActivity;
import com.getcapacitor.BridgeWebViewClient;

public class MainActivity extends BridgeActivity {

    private WebView appWebView;
    private LinearLayout offlineOverlay;
    private boolean pageErrored = false;
    private int autoRetryCount = 0;
    private final android.os.Handler autoRetryHandler = new android.os.Handler();

    private final ConnectivityManager.NetworkCallback networkCallback = new ConnectivityManager.NetworkCallback() {
        @Override
        public void onAvailable(Network network) {
            // Internet is back: hide the offline screen and reload automatically
            runOnUiThread(() -> {
                autoRetryCount = 0;
                boolean needsReload = pageErrored
                        || (offlineOverlay != null && offlineOverlay.getVisibility() == View.VISIBLE);
                hideOfflineOverlay();
                if (needsReload && appWebView != null) {
                    appWebView.reload();
                }
            });
        }

        @Override
        public void onLost(Network network) {
            // Connection dropped: show the offline screen instead of Android's error page
            runOnUiThread(() -> showOfflineOverlay());
        }
    };

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        appWebView = bridge != null ? bridge.getWebView() : null;
        if (appWebView == null) return;

        appWebView.setWebViewClient(new BridgeWebViewClient(bridge) {
            @Override
            public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                super.onReceivedError(view, request, error);
                if (request.isForMainFrame()) {
                    pageErrored = true;
                    runOnUiThread(() -> {
                        showOfflineOverlay();
                        scheduleAutoRetry();
                    });
                }
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                pageErrored = false;
                autoRetryCount = 0;
                autoRetryHandler.removeCallbacksAndMessages(null);
                if (isNetworkAvailable()) {
                    runOnUiThread(() -> hideOfflineOverlay());
                }
            }
        });

        buildOfflineOverlay();
        registerNetworkCallback();

        // If the app starts without internet, show the offline screen right away
        if (!isNetworkAvailable()) {
            showOfflineOverlay();
        }
    }

    @Override
    public void onResume() {
        super.onResume();
        if (isNetworkAvailable()) {
            // Auto-recover when the user comes back to the app after connection was restored
            if (pageErrored || (offlineOverlay != null && offlineOverlay.getVisibility() == View.VISIBLE)) {
                hideOfflineOverlay();
                if (appWebView != null) {
                    appWebView.reload();
                }
            }
        } else {
            showOfflineOverlay();
        }
    }

    @Override
    public void onDestroy() {
        autoRetryHandler.removeCallbacksAndMessages(null);
        try {
            ConnectivityManager cm = (ConnectivityManager) getSystemService(CONNECTIVITY_SERVICE);
            if (cm != null) {
                cm.unregisterNetworkCallback(networkCallback);
            }
        } catch (Exception ignored) {
        }
        super.onDestroy();
    }

    private void registerNetworkCallback() {
        ConnectivityManager cm = (ConnectivityManager) getSystemService(CONNECTIVITY_SERVICE);
        if (cm == null) return;
        NetworkRequest request = new NetworkRequest.Builder()
                .addCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET)
                .build();
        try {
            cm.registerNetworkCallback(request, networkCallback);
        } catch (Exception ignored) {
        }
    }

    private boolean isNetworkAvailable() {
        ConnectivityManager cm = (ConnectivityManager) getSystemService(CONNECTIVITY_SERVICE);
        if (cm == null) return false;
        Network network = cm.getActiveNetwork();
        if (network == null) return false;
        NetworkCapabilities caps = cm.getNetworkCapabilities(network);
        return caps != null && caps.hasCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET);
    }

    private void scheduleAutoRetry() {
        // Mobile data networks sometimes fail DNS momentarily; retry silently a few times
        if (autoRetryCount >= 3) return;
        if (!isNetworkAvailable()) return;
        autoRetryCount++;
        autoRetryHandler.postDelayed(() -> {
            if (pageErrored && appWebView != null) {
                appWebView.reload();
                scheduleAutoRetry();
            }
        }, 3000);
    }

    private void buildOfflineOverlay() {
        float density = getResources().getDisplayMetrics().density;
        int pad24 = (int) (24 * density);

        offlineOverlay = new LinearLayout(this);
        offlineOverlay.setOrientation(LinearLayout.VERTICAL);
        offlineOverlay.setGravity(Gravity.CENTER);
        offlineOverlay.setBackgroundColor(Color.parseColor("#FFFFFF"));
        offlineOverlay.setVisibility(View.GONE);
        offlineOverlay.setClickable(true);
        offlineOverlay.setFocusable(true);

        TextView title = new TextView(this);
        title.setText("No Internet Connection");
        title.setTextColor(Color.parseColor("#111111"));
        title.setTextSize(TypedValue.COMPLEX_UNIT_SP, 20);
        title.setTypeface(Typeface.DEFAULT_BOLD);
        title.setGravity(Gravity.CENTER);

        TextView message = new TextView(this);
        message.setText("Please check your internet connection and try again.");
        message.setTextColor(Color.parseColor("#666666"));
        message.setTextSize(TypedValue.COMPLEX_UNIT_SP, 14);
        message.setGravity(Gravity.CENTER);
        message.setPadding(0, (int) (8 * density), 0, 0);

        Button retry = new Button(this);
        retry.setText("Retry");
        retry.setTextColor(Color.WHITE);
        retry.setAllCaps(false);
        retry.setPadding(pad24, (int) (10 * density), pad24, (int) (10 * density));
        GradientDrawable retryBg = new GradientDrawable();
        retryBg.setColor(Color.parseColor("#0D6EFD"));
        retryBg.setCornerRadius(10 * density);
        retry.setBackground(retryBg);
        retry.setOnClickListener(v -> {
            if (isNetworkAvailable()) {
                hideOfflineOverlay();
                if (appWebView != null) {
                    appWebView.reload();
                }
            } else {
                Toast.makeText(this, "Still offline. Please check your connection.", Toast.LENGTH_SHORT).show();
            }
        });

        LinearLayout.LayoutParams retryParams = new LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        retryParams.topMargin = (int) (24 * density);

        offlineOverlay.addView(title);
        offlineOverlay.addView(message);
        offlineOverlay.addView(retry, retryParams);
        offlineOverlay.setPadding(pad24, pad24, pad24, pad24);

        ViewGroup content = (ViewGroup) findViewById(android.R.id.content);
        if (content != null) {
            content.addView(offlineOverlay, new ViewGroup.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
        }
    }

    private void showOfflineOverlay() {
        if (offlineOverlay != null) {
            offlineOverlay.setVisibility(View.VISIBLE);
        }
    }

    private void hideOfflineOverlay() {
        if (offlineOverlay != null) {
            offlineOverlay.setVisibility(View.GONE);
        }
    }
}
