import telebot
import requests

TOKEN = '6695191908:AAFF6Oz4SLDrr-8yLNPj0WpH2v9dhiMTEH4'

# Створюємо об'єкт бота
bot = telebot.TeleBot(TOKEN)
users = {}
class User:
    def __init__(self):
        self.id = None
        self.token = None
        self.task = None
        self.baseURL = 'http://localhost:8000/api'    
    
    def login(self, email, password):
        url = self.baseURL + '/login'
        data = {'email': email, 'password': password}
        response = requests.post(url, data=data)
        dataJson = response.json() 
        if response.status_code == 200:
            self.id = dataJson['id']
            self.token = dataJson['token']
            self.getTask()
        return response.status_code == 200

    def getTask(self):
        url = self.baseURL + '/get.task'
        data = {'token': self.token, 'id': self.id}
        response = requests.get(url, params=data)
        if response.status_code == 200:
            self.task = response.json() 
        return response.status_code == 200

    def createTask(self, task):
        url = self.baseURL + '/create.task'
        data = {'token': self.token, 'id': self.id, 'task' : task}
        response = requests.post(url, data=data)
        return response.status_code == 200


    def updateTask(self, task_id, task,is_completed):
        url = self.baseURL + '/create.task'
        data = {'token': self.token, 'id': self.id, 'task_id' : task_id, 'task' : task,'is_completed' : is_completed }
        response = requests.put(url, data=data)
        return response.status_code == 200

        
@bot.message_handler(commands=['start'])
def handle_start(message):
    bot.send_message(message.chat.id, "Привіт! Це To_du бот /login для авторизації.")

@bot.message_handler(commands=['login'])
def handle_login(message):
    words = message.text.split()
    if not len(words) == 3:
        bot.send_message(message.chat.id, "Невірно введена команда /login @email @password")
        return
    if not '@' in words[1]:
        bot.send_message(message.chat.id, "Невірно введено email")
        return
    user_id = message.from_user.id
    users[user_id] = User()
    if(users[user_id].login(words[1], words[2])):
        bot.send_message(message.chat.id, "Успішно авторизувався")
    else:
        del users[user_id]
        bot.send_message(message.chat.id, "Неправильний email або пароль")


@bot.message_handler(commands=['reload'])
def handle_reload(message):
    user_id = message.from_user.id
    if user_id not in users:
        bot.send_message(message.chat.id, "Потрібно авторизуватись /login @email @password")
        return
    if users[user_id].getTask():
        bot.send_message(message.chat.id, "Завдання успішно оновлені")
    else:
        bot.send_message(message.chat.id, "Проблеми із оновленням завдань")


@bot.message_handler(commands=['task'])
def handle_task(message):
    user_id = message.from_user.id
    if user_id not in users:
        bot.send_message(message.chat.id, "Потрібно авторизуватись /login @email @password")
        return
    myTask = users[user_id].task
    bot.send_message(message.chat.id, "Ваші завдання:\n\n")
    for task in myTask['task']:
        bot.send_message(message.chat.id, f"Завдання: {task['task']}\nId: {task['id']}\nВиконано: {'Так' if task['is_completed'] == 1 else 'Ні'}\n")

@bot.message_handler(commands=['create_task'])
def handle_create_task(message):
    words = message.text.split()
    user_id = message.from_user.id
    if user_id not in users:
        bot.send_message(message.chat.id, "Потрібно авторизуватись /login @email @password")
        return
    if not len(words) > 1:
        bot.send_message(message.chat.id, "Ви не ввели завдання")
        return
    words = message.text.replace('/create_task ','')
    if users[user_id].createTask(words):
        bot.send_message(message.chat.id, "Успішно створено нове завдання")
    else:
        bot.send_message(message.chat.id, "Завдання не створено")


@bot.message_handler(commands=['update_task'])
def handle_update_task(message):
    user_id = message.from_user.id
    if user_id not in users:
        bot.send_message(message.chat.id, "Потрібно авторизуватись /login @email @password")
        return
    words = message.text.replace('/update_task ','')
    words = words.split('|')
    if not len(words) == 3:
        bot.send_message(message.chat.id, "Ви не правильно заповнили завдання task_id|task|is_completed")
        return

    print(words)
    if users[user_id].updateTask(words[0],words[1],words[2]):
        bot.send_message(message.chat.id, "Завдання оновлено успішно")
    else:
        bot.send_message(message.chat.id, "проблеми із зєднанням до серверу")


# Запускаємо бота
if __name__ == "__main__":
    bot.polling(none_stop=True)
