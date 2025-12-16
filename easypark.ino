#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ESP32Servo.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

const char* ssid       = "Redmi Note 11S";
const char* password   = "belchior";
const char* serverName = "http://172.23.96.242/Easy-Park/api/inserir_acesso.php";

#define RC522_SS_PIN 5
#define RC522_RST_PIN 27

#define SERVO_PIN 14
#define TRIG_PIN 32
#define ECHO_PIN 33

LiquidCrystal_I2C lcd(0x3F, 16, 2);
MFRC522 mfrc522(RC522_SS_PIN, RC522_RST_PIN);
Servo cancela;

String lastUID = "";
unsigned long lastRead = 0;

// ========= SERVO =========
void inicializarServo() {
  cancela.attach(SERVO_PIN, 500, 2400);
  cancela.write(0);
  Serial.println("Servo pronto");
}

// ========= LCD =========
void lcdIdle() {
  lcd.clear();
  lcd.setCursor(1,0);
  lcd.print("EASY PARK IPS");
  lcd.setCursor(1,1);
  lcd.print("Aproxime cartao");
}

// ========= UID =========
String uidToString() {
  String s = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) s += "0";
    s += String(mfrc522.uid.uidByte[i], HEX);
  }
  s.toUpperCase();
  return s;
}

// ========= Sensor =========
float lerDistancia() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);
  long dur = pulseIn(ECHO_PIN, HIGH, 30000);
  return (dur == 0 ? 999 : (dur * 0.034) / 2);
}

// ========= Cancela =========
void abrirCancela() {
  cancela.write(90);
  lcd.clear();
  lcd.print("CANCELA ABERTA");
}

void fecharCancela() {
  cancela.write(0);
  lcd.clear();
  lcd.print("CANCELA FECHADA");
  delay(900);
  lcdIdle();
}

void cicloCancela() {
  abrirCancela();
  unsigned long semCarro = millis();

  while (true) {
    float d = lerDistancia();
    Serial.printf("Dist: %.1f cm\n", d);

    if (d > 6) {
      if (millis() - semCarro > 1200) break;
    } else {
      semCarro = millis();
    }
    delay(100);
  }
  fecharCancela();
}

// ========= HTTP =========
String enviarAoServidor(String uid, String &nome) {

  HTTPClient http;
  http.begin(serverName);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String post = "numero_cartao=" + uid + "&id_parque=1";
  int code = http.POST(post);

  if (code <= 0) {
    http.end();
    return "erro";
  }

  String r = http.getString();
  http.end();

  Serial.println("Resposta: " + r);

  if (!r.startsWith("OK|")) return r; // devolve erro especial

  int p1 = r.indexOf("|", 3);
  int p2 = r.indexOf("|", p1+1);

  String tipo = r.substring(3, p1);
  nome = r.substring(p1+1);

  return tipo;
}

// ========= SETUP =========
void setup() {
  Serial.begin(115200);

  Wire.begin(21, 22);
  lcd.init();
  lcd.backlight();
  lcdIdle();

  SPI.begin(18,19,23,RC522_SS_PIN);
  mfrc522.PCD_Init();

  inicializarServo();

  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) delay(200);
  Serial.println("WiFi ligado!");
}

// ========= LOOP =========
void loop() {

  if (!mfrc522.PICC_IsNewCardPresent()) return;
  if (!mfrc522.PICC_ReadCardSerial()) return;

  String uid = uidToString();

  // MOSTRAR UID NO SERIAL
  Serial.println("UID LIDO: " + uid);

  if (uid == lastUID && millis() - lastRead < 1500) return;
  lastUID = uid;
  lastRead = millis();

  lcd.clear();
  lcd.print("VALIDANDO...");

  String nome = "";
  String tipo = enviarAoServidor(uid, nome);

  if (tipo == "entrada") {
    lcd.clear();
    lcd.print("Bem-vindo,");
    lcd.setCursor(0,1);
    lcd.print(nome);
    delay(800);
    cicloCancela();
  }
  else if (tipo == "saida") {
    lcd.clear();
    lcd.print("Boa viagem,");
    lcd.setCursor(0,1);
    lcd.print(nome);
    delay(800);
    cicloCancela();
  }
  else if (tipo.indexOf("parque_cheio") >= 0) {
    lcd.clear();
    lcd.print("PARQUE LOTADO");
    delay(1500);
    lcdIdle();
  }
  else if (tipo.indexOf("cartao_invalido") >= 0) {
    lcd.clear();
    lcd.print("CARTAO INVALIDO");
    delay(1500);
    lcdIdle();
  }
  else {
    lcd.clear();
    lcd.print("ACESSO NEGADO");
    delay(1200);
    lcdIdle();
  }

  mfrc522.PICC_HaltA();
  delay(200);
}
