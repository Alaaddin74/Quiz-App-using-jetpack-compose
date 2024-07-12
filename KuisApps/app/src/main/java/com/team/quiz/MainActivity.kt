package com.team.quiz

import android.os.Bundle
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Lock
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Text
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Surface
import androidx.compose.material3.TextField
import androidx.compose.material3.TextFieldDefaults
import androidx.compose.material3.TopAppBar
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.paint
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavHostController
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import com.team.quiz.ui.theme.KuisAppsTheme
import com.team.quiz.ui.theme.GreenJC
import com.team.quiz.ui.theme.Purple40

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            KuisAppsTheme {
                Box(modifier = Modifier
                    .fillMaxSize()
                    .paint(
                        painterResource(id = R.drawable.login2),
                        contentScale = ContentScale.FillBounds
                    )) {
                    val navController = rememberNavController()
                    NavGraph(navController)
                }
            }
        }
    }
}

@Composable
fun LoginPage(onLoginSuccess: () -> Unit) {
    var username by remember { mutableStateOf(value = "") }
    var password by remember { mutableStateOf(value = "") }
    var text by remember { mutableStateOf("") }
    val context = LocalContext.current

    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(horizontal = 26.dp, vertical = 140.dp),
        verticalArrangement = Arrangement.Bottom,
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        OutlinedTextField(
            value = username, onValueChange = { username = it },
            label = { Text(text = "Username") },
            shape = RoundedCornerShape(20.dp),
            colors = TextFieldDefaults.colors(
                focusedLeadingIconColor = GreenJC,
                unfocusedLeadingIconColor = GreenJC,
                focusedLabelColor = GreenJC,
                unfocusedLabelColor = GreenJC,
                focusedContainerColor = Color.White,
                unfocusedContainerColor = Color.White,
                focusedIndicatorColor = GreenJC,
                unfocusedIndicatorColor = GreenJC,
                unfocusedPlaceholderColor = GreenJC
            ), leadingIcon = {
                Icon(imageVector = Icons.Default.Person, contentDescription = "Username")
            },
            modifier = Modifier
                .fillMaxWidth()
                .padding(bottom = 8.dp)
        )

        OutlinedTextField(
            value = password, onValueChange = { password = it },
            label = { Text(text = "Password") },
            shape = RoundedCornerShape(20.dp),
            colors = TextFieldDefaults.colors(
                focusedLeadingIconColor = Purple40,
                unfocusedLeadingIconColor = Purple40,
                focusedLabelColor = Purple40,
                unfocusedLabelColor = Purple40,
                focusedContainerColor = Color.White,
                unfocusedContainerColor = Color.White,
                focusedIndicatorColor = Purple40,
                unfocusedIndicatorColor = Purple40,
                unfocusedPlaceholderColor = Purple40
            ), leadingIcon = {
                Icon(imageVector = Icons.Default.Lock, contentDescription = "Password")
            },
            modifier = Modifier
                .fillMaxWidth()
                .padding(bottom = 8.dp),
            visualTransformation = PasswordVisualTransformation()
        )


        Button(
            onClick = {
                if (username.isEmpty() || password.isEmpty()) {
                    Toast.makeText(context, "Please fill in both fields", Toast.LENGTH_SHORT).show()
                } else if (authenticate(username, password)) {
                    onLoginSuccess()
                    Toast.makeText(context, "Login Successful", Toast.LENGTH_SHORT).show()
                } else {
                    Toast.makeText(context, "Invalid Credentials", Toast.LENGTH_SHORT).show()
                }
            },
            colors = ButtonDefaults.buttonColors(containerColor = GreenJC),
            contentPadding = PaddingValues(start = 60.dp, end = 60.dp, top = 8.dp, bottom = 8.dp),
            modifier = Modifier.padding(top = 18.dp)
        ) {
            Text(text = "Login", fontSize = 22.sp)
        }
    }
}

private fun authenticate(username: String, password: String): Boolean {
    val validUsername = "admin"
    val validPassword = "admin"
    return username == validUsername && password == validPassword
}

@Composable
fun NavGraph(navController: NavHostController) {
    NavHost(navController = navController, startDestination = "login") {
        composable("login") {
            LoginPage(onLoginSuccess = {
                navController.navigate("home") {
                    popUpTo(0) { inclusive = true }
                }
            })
        }
        composable("home") {
            QuizScreen()
        }
    }
}

@Preview
@Composable
fun MyAppPreview() {
    KuisAppsTheme {
        QuizScreen()
//        val navController = rememberNavController()
//        NavGraph(navController)
    }
}
