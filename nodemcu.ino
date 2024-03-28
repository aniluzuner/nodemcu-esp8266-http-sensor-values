#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <DHT.h>
// İlk olarak gerekli kütüphaneler dahil edilir.

const char *ssid = "anil";
const char *password = "anil12345";
// Wi-Fi bağlantısı için bağlanılacak ağın adı ve şifresi tanımlanır. 

const char *server = "aniluzuner.com";
const int port = 80; 
// Verilerin gönderileceği server adresi ve port tanımlanır.

WiFiClient client;
HTTPClient http;
/* Verilerin HTTP istekleri ile gönderilebilmesi için client 
ve http nesneleri oluşturulur. */

DHT dht(D7, DHT11);
/* Sıcaklık ve nem verilerinin okunabilmesi için pin ve sensör 
modeli parametreleri girilerek dht nesnesi oluşturulur. */

float sicaklik;
int nem;
// Sıcaklık ve nem değerlerinin atanacağı global değişkenler oluşturulur.

const int pir = D1;
const int kirmizi_led = D2;
const int yesil_led = D3;
// Ledlerin ve hareket sensörünün bağlı olduğu pinler ilgili değişkenlere atanır.

void setup() {
  dht.begin();
  // Sıcaklık ve nem verilerinin okunabilmesi için dht sensör başlatılır.

  sicaklik = dht.readTemperature();
  nem = dht.readHumidity();
  // İlk sıcaklık ve nem değerleri değişkenlere atanır.

  pinMode(pir, INPUT);
  // Hareket sensörünün bağlı olduğu pin giriş olarak ayarlanır.

  pinMode(kirmizi_led, OUTPUT);
  pinMode(yesil_led, OUTPUT);
  // Ledlerin bağlı olduğu pinler çıkış olarak ayarlanır.        

  Serial.begin(9600);
  // Seri iletişim başlatılır.

  WiFi.begin(ssid, password);
  // Wi-Fi bağlantısı başlatılır.

  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(yesil_led, HIGH);
    delay(500);
    Serial.println("Wi-Fi'a bağlanılıyor...");
    digitalWrite(yesil_led, LOW);
    delay(500);
  }
  // Wi-Fi bağlanmaya çalışırken yeşil led yarım saniyede bir yanıp söner.

  Serial.println("Wi-Fi bağlandı!");
  digitalWrite(yesil_led, HIGH);
  // Wi-Fi bağlandığında serial monitöre "Wi-Fi bağlandı!" yazar ve yeşil led yanar.
}


void loop() {
  if (sicaklik != dht.readTemperature() || nem != dht.readHumidity()){
    sicaklik = dht.readTemperature();
    nem = dht.readHumidity();

    String link = "/dht11.php?sicaklik=" + String(sicaklik) + "&nem=" + String(nem);
  
    http.begin(client, server, port, link);
    int httpCode = http.GET();

    http.end();
  }
  /* Eğer sıcaklık veya nem değeri bir önceki değerden farklıysa yeni değerler değişkene aktarılır,
  yeni sıcaklık ve nem parametreleri ile serverdaki dht11.php dosyasını çalıştıracak http isteği gönderilir. */     

  int sensorvalue = digitalRead(pir);
  // Hareket sensörü verisi okunur.
  
  if (sensorvalue == HIGH) {
    http.begin(client, server, port, "/motion.php");
    int httpCode = http.GET();
    Serial.println(httpCode);
    http.end();

    for (int i = 1; i <= 3; i++) {
      digitalWrite(kirmizi_led, HIGH);
      delay(300);
      digitalWrite(kirmizi_led, LOW);
      delay(300);
    }
    digitalWrite(kirmizi_led, LOW);
  }
  /* Eğer hareket algılandıysa serverdaki motion.php dosyasını çalıştıracak http isteği gönderilir
  ve kırmızı led 3 kere yanıp söner. */
}


