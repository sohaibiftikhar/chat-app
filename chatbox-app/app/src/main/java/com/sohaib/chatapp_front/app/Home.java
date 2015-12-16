package com.sohaib.chatapp_front.app;

import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.EditText;
import android.widget.Toast;


public class Home extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_home, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    public void displayError(String message){
        Toast.makeText(this,message, Toast.LENGTH_SHORT).show();
    }

    public void startConnecting(View view){
        WebView webview = new WebView(this);
        EditText ip = (EditText)findViewById(R.id.ip_addr);
        String ipAdd = ip.getText().toString();
        for( String n: ipAdd.split(".")){
            try {
                int num = Integer.parseInt(n);
                if(num>255 || num <0) {
                    displayError("IP-Addresses should be between 0 and 255");
                    return;
                }
            } catch(Exception e) {
                displayError("IP-Addresses should be between numbers");
                return;
            }
        }
        setContentView(webview);
        webview.setWebViewClient(new WebViewClient());
        webview.getSettings().setJavaScriptEnabled(true);
        webview.getSettings().setJavaScriptCanOpenWindowsAutomatically(true);
        webview.loadUrl("http://" + ipAdd);
    }
}
