#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
 
const char* ssid = "12345677";//12345677   B341Fe
const char* password = "0928344987";//0928344987   0999888777
char now_statetion[10];
char old_statetion[10];
int auto_state_old[5];
int auto_state_new[5];
const unsigned int device1_Pin1=5;    //D1
const unsigned int device1_Pin2=4;    //D2
const unsigned int device2_Pin1=0;    //D3
const unsigned int device2_Pin2=2;    //D4

const unsigned int modepin=13;        //D7

const unsigned int online=15;         //D8  LED(mode)
const unsigned int RST_line=14;       //D5
const unsigned int leakage=12;        //D6




int buttonline;
int modestate;
unsigned mode_func=0; //自動控制(恆亮)->1     人控制(閃爍)-> 0        
unsigned count=0;
unsigned seed=1;
unsigned seed_mode=0;
int line_lock=0;

int ledState = LOW;
char val[300];

void setup () {
	//old_statetion[1]='123';
	//old_statetion[2]='456';
	
  pinMode(online, OUTPUT);
  pinMode(device1_Pin1, OUTPUT);
  pinMode(device1_Pin2, OUTPUT);
  pinMode(device2_Pin1, OUTPUT);
  pinMode(device2_Pin2, OUTPUT);
  //pinMode(device3_Pin1, OUTPUT);
  //pinMode(device3_Pin2, OUTPUT);
  //pinMode(RST_line, INPUT_PULLUP);
  pinMode(leakage,INPUT);    //D6  INPUT_PULLUP
  pinMode(modepin,INPUT);    //D7
  
  
  Serial.begin(115200);
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(online, LOW);
    delay(1000);
    Serial.print("Connecting..");
    digitalWrite(online, HIGH);
  }
  

  
}
 
void loop() {
  count++;
  //int buttonState = digitalRead(RST_line);   //D5
  
  //Serial.println(buttonline);
	  
  if(count%300000==0){
    //buttonline=   digitalRead(leakage);    //D6
    //modestate=    digitalRead(modepin);    //D7 
	mode_catch();
    seed++;
	Serial.print("seed:");
	Serial.println(seed);
    //Serial.println(seed);
    //Serial.print(seed);
    /*
	if(modestate==1){
		mode_func=0;  //人控制
      }
	else{
		mode_func=1;  //自動控制
	}
    */
	
	
	Serial.print("mode_func");
	Serial.println(mode_func);
	
	//mode_func=int(now_statetion[4]);
    if(mode_func==48){ //ascii:'0'=48               // 人控制valves-> 0   (閃爍) 
      /*----閃爍----*/
      BlinkLed();
      /*------------*/   
      state_swapin_hand();
	  
      updating_led();
	  
      Serial.print("D6:");
      Serial.println(digitalRead(leakage));   
	  Serial.print("  ");
	  Serial.println(now_statetion[3]);  
                                                                 //digitalRead(leakage)==0  漏水     
      if( (digitalRead(leakage)==0) && (now_statetion[3]=='1') ){  
		  Serial.println("update 0");                            //now_statetion[3]==0      漏水
          update_db(0);                   //更新device3= N漏水                  
          pop_line("WaterLeakage");  
                                                     //device3=0      漏水
		                                                         //update_db(0)   漏水
		 /*                                                     
          if(line_lock==0){
             //pop_line("WaterLeakage");
             Serial.println("POP LINE");
             line_lock=1; 
          }
          */  
      }
      else if( (digitalRead(leakage)==1) && (now_statetion[3]=='0') ){            //沒漏  and 漏水
          Serial.println("update 1");
          update_db(1);   //更新device3=1  沒漏
		  pop_line("UnWaterLeakage");
          
      }
    }
	
	
	/*----------------------------------------------------------------------------------------*/
	
	
    else if(mode_func==49){   //ascii:'1'=49       //自動控制valves->1   (恆亮)
      /*----恆亮----*/
      LedHigh();
      /*------------*/
	  state_swapin_auto();
      if(digitalRead(leakage)==0){    //0漏水
        close_valves();
		update_db(0);
			if(auto_state_old[3]!=auto_state_new[3] ){
				line_lock=0;
				if(line_lock==0){
					pop_line("WaterLeakage");
					pop_line("Water_Valves_Closed");
					
					Serial.println("update device3=0");
					line_lock=1;
					//http://163.18.59.103/updatedata.php?device1=-1&device2=-1&device3=0&device4=-1 
				}
			}
      }
      else if((digitalRead(leakage))==1){
        open_valves();
		update_db(1);
			if(auto_state_old[3]!=auto_state_new[3]){
				line_lock=0;
				if(line_lock==0){
					pop_line("Water_Valves_Opened");
					
					Serial.println("update device3=1");
					line_lock=1;
				//http://163.18.59.103/updatedata.php?device1=-1&device2=-1&device3=1&device4=-1
				}
			}
	  }  
      
    }     
    
	}//if (count%200000==0) 

  
  
  
  if(seed%20==0){
    //pop_line();
    Serial.println("RST line_lock");
    line_lock=0;
    seed=1;
    //seed_mode=0;
    }
   

    
  
  //Serial.println(buttonline);


}
void update_db(int x){
  if(x==1){            
    if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
		HTTPClient http;  //Declare an object of class HTTPClient
		http.begin("http://163.18.59.103/updatedata.php?device1=-1&device2=-1&device3=1&device4=-1"); 
		int httpCode = http.GET();                                                                      
		if (httpCode > 0) { //Check the returning code
			String payload = http.getString();   
		}
		http.end();   //Close connection
    }
  }
  else if(x==0){      
		if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
			HTTPClient http;  //Declare an object of class HTTPClient
			http.begin("http://163.18.59.103/updatedata.php?device1=-1&device2=-1&device3=0&device4=-1"); 
			int httpCode = http.GET();                                                                      
			if (httpCode > 0) { //Check the returning code
				String payload = http.getString();   
			}
		http.end();   //Close connection
		}	 
  }
}


void change_mode(){
  if(mode_func==1){
     mode_func=0;
     Serial.println("mode=0");
     seed_mode=0;
     line_lock=0;
  }
   else{
     mode_func=1;
     Serial.println("mode=1");
     seed_mode=0;
     line_lock=0;
  }
  
  
  }
/*
void pop_line(){
  if(line_lock==0){
     if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
       HTTPClient http;  //Declare an object of class HTTPClient
       http.begin("http://163.18.59.103/botC.php?userid=Ucdad96ad0cf5861c48a4a37d9a9ad3ef&message=WaterLeakage");  //Specify request destination
       int httpCode = http.GET();                                                                  //Send the request
       http.end();   //Close connection
       line_lock=1;
     }
  }    
}
*/
void pop_line(char* data_input){
  char buf[300];
  sprintf(buf,"http://163.18.59.103/botC.php?userid=Ucdad96ad0cf5861c48a4a37d9a9ad3ef&message=%s",data_input);
  
     if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
       HTTPClient http;  //Declare an object of class HTTPClient
       http.begin(buf);  //Specify request destination
       int httpCode = http.GET();                                                                  //Send the request
       http.end();   //Close connection
       
     }
      
}
void state_swapin_hand(){
    if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
 
    HTTPClient http;  //Declare an object of class HTTPClient
 
    http.begin("http://163.18.59.103/catchlastdata.php");  //Specify request destination
    int httpCode = http.GET();                                                                  //Send the request
 
    if (httpCode > 0) { //Check the returning code
 
      String payload = http.getString();   //Get the request response payload
      Serial.println(payload);                     //Print the response payload

      old_statetion[1]=(now_statetion[1]);   //cold valve
      old_statetion[2]=(now_statetion[2]);   //hot valve
      old_statetion[3]=(now_statetion[3]);   //有沒有漏水?     1 NO漏水   0 漏水
      old_statetion[4]=(now_statetion[4]);   //mode_func 模式切換  1-自動控制  0-人控制
      
      now_statetion[1]=int(payload.charAt(1));
      now_statetion[2]=int(payload.charAt(3));
      now_statetion[3]=int(payload.charAt(5));
      now_statetion[4]=int(payload.charAt(7));

      //old_statetion[1]=(now_statetion[1]);
	  //old_statetion[2]=(now_statetion[2]);	
      
      //int x=payload.indexOf("a");
      //int y=payload.indexOf("z");
      
      
      Serial.print("now:");
      Serial.print(now_statetion[1]);
      Serial.print(" ");
      Serial.print(now_statetion[2]);
      Serial.print(" ");
      Serial.print(now_statetion[3]);
      Serial.print(" ");
      Serial.println(now_statetion[4]);
	  
	  
	  Serial.print("old:");
	  Serial.print(old_statetion[1]);
      Serial.print(" ");
      Serial.print(old_statetion[2]);
      Serial.print(" ");
      Serial.print(old_statetion[3]);
      Serial.print(" ");
      Serial.println(old_statetion[4]);
	  
	  
	  
	  
    }
 
    http.end();   //Close connection
 
  }
  
  
  }
  
void state_swapin_auto(){
    if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
 
    HTTPClient http;  //Declare an object of class HTTPClient
 
    http.begin("http://163.18.59.103/catchlastdata.php");  //Specify request destination
    int httpCode = http.GET();                                                                  //Send the request
 
    if (httpCode > 0) { //Check the returning code
 
      String payload = http.getString();   //Get the request response payload
      Serial.println(payload);                     //Print the response payload

      auto_state_old[1]=auto_state_new[1];   //cold valve
      auto_state_old[2]=auto_state_new[2];   //hot valve
      auto_state_old[3]=auto_state_new[3];   //有沒有漏水?     1 NO漏水   0 漏水
      auto_state_old[4]=auto_state_new[4];   //mode_func 模式切換  0-自動控制  1-人控制
      
      auto_state_new[1]=int(payload.charAt(1));
      auto_state_new[2]=int(payload.charAt(3));
      auto_state_new[3]=int(payload.charAt(5));
      auto_state_new[4]=int(payload.charAt(7));
	  
	  for(int q=1 ; q<5 ;q++){
		  if(auto_state_old[q]==49){
			  auto_state_old[q]=1;
		  }
		  else if(auto_state_old[q]==48){
			  auto_state_old[q]=0;
		  }
	  }  
	  for(int q=1 ; q<5 ;q++){
		  if(auto_state_new[q]==49){
			  auto_state_new[q]=1;
		  }
		  else if(auto_state_new[q]==48){
			  auto_state_new[q]=0;
		  }  
	  }	
      
      
      Serial.print("now:");
      Serial.print(auto_state_new[1]);
      Serial.print(" ");
      Serial.print(auto_state_new[2]);
      Serial.print(" ");
      Serial.print(auto_state_new[3]);
      Serial.print(" ");
      Serial.println(auto_state_new[4]);
	  
	  
	  Serial.print("old:");
	  Serial.print(auto_state_old[1]);
      Serial.print(" ");
      Serial.print(auto_state_old[2]);
      Serial.print(" ");
      Serial.print(auto_state_old[3]);
      Serial.print(" ");
      Serial.println(auto_state_old[4]);
	  
	  
	  
	  
    }
 
    http.end();   //Close connection
 
  }
  
  
  }  
  
void mode_catch(){
    if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
		HTTPClient http;  //Declare an object of class HTTPClient
		http.begin("http://163.18.59.103/catchlastdata.php");  //Specify request destination
		int httpCode = http.GET();                                                                  //Send the request
		if (httpCode > 0) { //Check the returning code
			String payload = http.getString();   //Get the request response payload
			mode_func=int(payload.charAt(7)); 
    }
    http.end();   //Close connection
    } 
}  
  
void updating_led(){
     if(now_statetion[1]=='1'){
       digitalWrite(device1_Pin1, HIGH);
       digitalWrite(device1_Pin2, LOW);
       //delay(5000);

     }
     else if(now_statetion[1]=='0'){
       digitalWrite(device1_Pin1, LOW);
       digitalWrite(device1_Pin2, HIGH);
       //delay(5000);

     }
/*------------------------------------------*/
     if(now_statetion[2]=='1'){
       digitalWrite(device2_Pin1, HIGH);
       digitalWrite(device2_Pin2, LOW);
       //delay(5000);

     }
     else if(now_statetion[1]=='0'){
       digitalWrite(device2_Pin1, LOW);
       digitalWrite(device2_Pin2, HIGH);
       //delay(5000);
  
    }  
 }
void close_valves(){

  digitalWrite(device1_Pin1, LOW);
  digitalWrite(device1_Pin2, HIGH);
  digitalWrite(device2_Pin1, LOW);
  digitalWrite(device2_Pin2, HIGH);
  update_db_close_valves();
  Serial.println("close_valves");
    
  }    
void open_valves(){

  digitalWrite(device1_Pin1, HIGH);
  digitalWrite(device1_Pin2, LOW);
  digitalWrite(device2_Pin1, HIGH);
  digitalWrite(device2_Pin2, LOW);
  update_db_open_valves();
  Serial.println("open_valves");
  
  } 
void BlinkLed(){
      if (ledState == LOW) {
        ledState = HIGH;
      } else {
        ledState = LOW;
      }
      digitalWrite(online, ledState); 
  }  
void LedHigh(){
  digitalWrite(online, HIGH);
  }
void update_db_close_valves(){
	if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
    HTTPClient http;  //Declare an object of class HTTPClient
    http.begin("http://163.18.59.103/updatedata.php?device1=0&device2=0&device3=-1&device4=-1");  //Specify request destination
    int httpCode = http.GET();                                                                  //Send the request
    if (httpCode > 0) { //Check the returning code
      String payload = http.getString();   //Get the request response payload
      //Serial.println(payload);                     //Print the response payload
    }
    http.end();   //Close connection
  }	
}
void update_db_open_valves(){
	if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
    HTTPClient http;  //Declare an object of class HTTPClient
    http.begin("http://163.18.59.103/updatedata.php?device1=1&device2=1&device3=-1&device4=-1");  //Specify request destination
    int httpCode = http.GET();                                                                  //Send the request
    if (httpCode > 0) { //Check the returning code
      String payload = http.getString();   //Get the request response payload
      //Serial.println(payload);                     //Print the response payload
    }
    http.end();   //Close connection
  }	
}
