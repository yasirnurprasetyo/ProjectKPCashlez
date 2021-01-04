const jsonServer = require('json-server')
const express = require('express');
const app = express();
const jwt = require('jsonwebtoken')
const bcrypt = require('bcrypt')
const bodyParser = require('body-parser');
const { Session } = require('inspector');
const { decode } = require('punycode');
const router = jsonServer.router('db.json')
const middlewares = jsonServer.defaults()

// Set default middlewares (logger, static, cors and no-cache)
app.use(middlewares)
app.use(bodyParser.json());

// Dummy User
const users = [];
const usersx = [];

// Login register user testing
app.post('/session/register', async (req, res) => {
	try {
		const checkUser = users.find(user => user.username === req.body.username)
		if (checkUser && req.body.username === checkUser.username) return res.status(403).send('User already exxists')
		const hashedPass = await bcrypt.hash(req.body.password, 10)
		const user = {
			username: req.body.username,
			password: hashedPass
		}
		// adding to the dummy users
		users.push(user)
		return res.status(201).send('Registered successfully')
	} catch {
		return res.status(500).send('Something went wrong')
	}
})

// Post Login
app.post('/api/session/login', async (req, res) => {
	const user = users.find(user => user.username === req.body.username)
	if (user === null) return res.status(400).send('User not found!')
	if (await bcrypt.compare(req.body.password, user.password)) {
		const session_token = jwt.sign({ username: user.username }, 'TheSecretKey', { expiresIn: "1 days" });
		const access_token = jwt.sign({ password: user.password }, 'TheSecretKey', { expiresIn: "1h" });
		const sess = {
			session_token: session_token,
			access_token: access_token
		}
		usersx.push(sess)
		res.jsonp(usersx).status(200)
	}
	else {
		res.status(404)
	}
})

// Add custom routes before JSON Server router
app.get('/api/sales-group/monthly', verifyToken, (req, res) => {
	const token_access = req.headers.token
	const tok = usersx.find(user => user.access_token)
	const cek = token_access == tok.access_token
	if (!cek) return res.status(401).send('Session Access tidak ditemukan')
	const groupID = req.body.group_code
	var db = router.db
	var tasks = db
		.get('monthly')
		.find({ group_code: groupID })
		.value()
	if (tasks) {
		res.jsonp(tasks).status(200)

	} else {
		res.status(404)
	}
})

// Get data monthly details
app.get('/api/sales-group/monthly-details', verifyToken, (req, res) => {
	const token_access = req.headers.token
	const tok = usersx.find(user => user.access_token)
	const cek = token_access == tok.access_token
	if (!cek) return res.status(401).send('Session Access tidak ditemukan')
	const groupID = req.body.group_code
	var db = router.db // lowdb instance
	var tasks = db
		.get('monthly-details')
		.find({ group_code: groupID })
		.value()
	if (tasks) {
		res.jsonp(tasks)
	} else {
		res.sendStatus(401)
	}
})

// Session Refresh
app.post('/api/session/refresh', verifyToken, (req, res) => {
	// Get Parameter Session Token
	const authHeader = req.headers['authorization']
	const session_token = authHeader && authHeader.split(' ')[1]
	// Get Parameter Username
	const username = req.body.username
	// Cek Username and Cek Session Token
	const user = users.find(user => user.username === username)
	const cekSession = usersx.find(cekSession => cekSession.session_token === session_token)
	const cektoken = cekSession.session_token == session_token
	const cekuser = user.username == username
	if(cektoken && cekuser) {
		// Create Access Token New
		const access_token = jwt.sign({ password: user.password }, 'TheSecretKey', { expiresIn: "1h" });
		const refresh = usersx.access_token = access_token
		usersx.pop(refresh)
		const sess = {
			session_token: session_token,
			access_token: access_token
		}
		usersx.push(sess)
		res.jsonp(usersx).status(200)
	}else {
		res.status(404)
	}
})

// Sales Group Login
app.get('/api/sales-group/login', verifyToken, (req, res) => {
	const token_access = req.headers.token
	const tok = usersx.find(user => user.access_token)
	const cek = token_access == tok.access_token
	if (!cek) return res.status(401).send('Sales Group tidak ditemukan')
	const g_code = req.body.group_code
	const pass = req.body.password
	var db = router.db // lowdb instance
	var tasks = db
		.get('login')
		.find({ group_code: g_code })
		.value()
	if (tasks) {
		res.jsonp(tasks)
	} else {
		res.sendStatus(401)
	}
})

// Check Session Token
function verifyToken(req, res, next) {
	//Token shape: Bearer ey....
	const authHeader = req.headers['authorization']
	const token = authHeader && authHeader.split(' ')[1]
	const tok = usersx.find(user => user.session_token)
	const cek = tok.session_token === token
	if (token === null) return res.status(401).send('Session tidak ditemukan')
	if (!cek) return res.status(404).send('Session Habis')
	jwt.verify(token, "TheSecretKey", (err, user) => {
		if (err) return res.status(403).send('Unauthorized access')
		req.user = user
		next()
	})
}

// Use default router
app.use(router)
app.listen(1323, () => {
	console.log('JSON Server is running')
})