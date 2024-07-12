package com.team.quiz

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.AccountCircle
import androidx.compose.material.icons.filled.Favorite
import androidx.compose.material3.Button
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableIntStateOf
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.produceState
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import org.json.JSONArray
import java.net.HttpURLConnection
import java.net.URL

data class Question(
    val id: Int,
    val question: String,
    val options: List<String>,
    val correctOption: String
)

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun QuizScreen() {

    var currentQuestion by remember { mutableIntStateOf(0) }
    var score by remember {          mutableStateOf(0) }


    Scaffold(
        topBar = {
            TopAppBar(
                colors = TopAppBarDefaults.centerAlignedTopAppBarColors(
                    containerColor = MaterialTheme.colorScheme.primaryContainer,
                    titleContentColor = MaterialTheme.colorScheme.primary,
                ),
                title = {
                    Text("Quiz app")
                },
                actions = {
                    IconButton(onClick = {
                        /* doSomething */
                    }) {
                        Icon(Icons.Filled.Favorite, contentDescription = "Favorite")
                    }
                    IconButton(onClick = {
                        /* doSomething */
                    }) {
                        Icon(Icons.Filled.AccountCircle, contentDescription = "Account Circle")
                    }
                }
            )
        },
    ) { innerPadding ->
        Column(
            modifier = Modifier
                .padding(innerPadding)
                .background(Color.Cyan)
                .fillMaxSize(),
            verticalArrangement = Arrangement.spacedBy(16.dp),
        ) {
            if (showResults) {
                Column(
                    modifier = Modifier.padding(16.dp)
                ) {
                    Text(text = "Your score: $score/${questions.size}", style = MaterialTheme.typography.bodyMedium)
                    Spacer(modifier = Modifier.height(16.dp))
                    Button(
                        onClick = {
                            currentQuestion = 0
                            score = 0
                            showResults = false
                            fetchTrigger = !fetchTrigger // Trigger fetchQuestions
                        },
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(vertical = 4.dp)
                    ) {
                        Text(text = "Restart Quiz")
                    }
                }
            } else {
                Column(
                    modifier = Modifier.padding(16.dp)
                ) {
                    val currentQ = questions[currentQuestion]
                    Text(text = "Score: $score", style = MaterialTheme.typography.bodySmall)
                    Spacer(modifier = Modifier.height(16.dp))
                    Text(text = currentQ.question, style = MaterialTheme.typography.headlineLarge)
                    Spacer(modifier = Modifier.height(16.dp))
                    currentQ.options.forEach { answer ->
                        Button(
                            onClick = {
                                if (answer == currentQ.correctOption) {
                                    score++
                                }
                                if (currentQuestion < questions.size - 1) {
                                    currentQuestion++
                                } else {
                                    showResults = true
                                }
                            },
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(vertical = 4.dp)
                        ) {
                            Text(text = answer)
                        }
                    }
                }
            }
        }
    }
}

suspend fun fetchQuestions(): List<Question> {
    return withContext(Dispatchers.IO) {
        val url = URL("http://172.20.10.10/quiz/get_question.php")
        val connection = url.openConnection() as HttpURLConnection

        connection.requestMethod = "GET"

        val response = connection.inputStream.bufferedReader().readText()
        val jsonArray = JSONArray(response)

        val questions = mutableListOf<Question>()
        for (i in 0 until jsonArray.length()) {
            val jsonObject = jsonArray.getJSONObject(i)
            val id = jsonObject.getInt("id")
            val question = jsonObject.getString("question")
            val options = listOf(
                jsonObject.getString("option_a"),
                jsonObject.getString("option_b"),
                jsonObject.getString("option_c"),
                jsonObject.getString("option_d")
            )
            val correctOption = options[jsonObject.getString("correct_option")[0] - 'A']
            questions.add(Question(id, question, options, correctOption))
        }
        questions
    }
}
