# Project_End_IT
# Update 08.12.25 


# hardware || software || Program
	ESP32, IR sensor, Ultrasonic sensor, LCD 16x2/OLED


# Arduino IDE Library
		WiFi.h → เชื่อม WiFi
		HTTPClient.h → POST ข้อมูลไป API
        #เซนเซอร์ library เช่น
	•IR / Ultrasonic
	•HX711 loadcell
    •หน้าจอ LCD
	•LiquidCrystal_I2C.h
	•Adafruit_SSD1306


# ภาษาที่ใช้ 
    Html, php, css, Js


# Program 
    xmpp
    node.js


# ขั้นตอนการนำเอาไปใช้กับPCอื่น
		-โหลด xmpp and node.js จากนั้น run program xmpp ขึ้นมาแล้วกดStrat Apacahe and MySql
			-จากนั้นให้แก้ database ของPCที่ใช้รัน xmpp โดยหลังจากรัน Apacahe and Mysql แล้ว ให้กด Admin ของ MySQL (URL TO GO: http://localhost/phpmyadmin/)
			-ขั้นตอนการรันweb หาไฟล์ xmpp ที่ติดตั้งแล้วไปที่ folder"htdocs"สร้างFloderแล้วโหลดไฟล์ ("bottle-exchange.zip or bottle-exchange")แล้วเอาไปใส่ในfloder htdocs จากนั้นกด Admin ของ Apcahe จากนั้นเลือกตามนี้ bottle-exchange/Proj/login.html


# Path file
	xampp\htdocs\bottle-exchange\Proj

# code Arduino 
	#include <WiFi.h>
	#include <HTTPClient.h>

	const char* ssid = "PCPun"; //YOUR_WIFI
	const char* password = "123456789"; //YOUR_PASSWORD

	// แก้เป็นโดเมนหรือ IP ของปั้น
	String serverUrl = "http://192.168.1.1/bottle-exchange/Proj/updateBottle.php";

	// ใส่ studentId ที่ login อยู่
	String studentId = "1113";
	
	void setup() {
  	Serial.begin(115200);

  	WiFi.begin(ssid, password);
  	Serial.print("Connecting WiFi");

 	 while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
 	 }
	
	  Serial.println("\nWiFi connected");
	}

	void loop() {
  	delay(5000);

  	// จำลองว่ามีขวดเข้า
  	Serial.println(">>> Simulate bottle detected");
  	sendBottle(1);
	}

	void sendBottle(int bottle) {
  	if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    String url = serverUrl + "?studentId=" + studentId + "&bottle=" + String(bottle);
    http.begin(url);

    int httpCode = http.GET();

    Serial.print("HTTP Code: ");
    Serial.println(httpCode);

    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("Response: " + response);
    }

    http.end();
  	}
	}

