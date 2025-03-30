const express = require("express");
const mongoose = require("mongoose");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const cors = require("cors");
const bodyParser = require("body-parser");
require("dotenv").config();

const app = express();
app.use(cors());
app.use(bodyParser.json());

// **MongoDB Connection**
mongoose
    .connect("mongodb://localhost:27017", { useNewUrlParser: true, useUnifiedTopology: true })
    .then(() => console.log("MongoDB Connected"))
    .catch((err) => console.log(err));

// **User Schema**
const userSchema = new mongoose.Schema({
    username: { type: String, unique: true, required: true },
    name: { type: String, required: true },
    emailid: { type: String, unique: true, required: true },
    password: { type: String, required: true }
});

const User = mongoose.model("User", userSchema);

// **SIGNUP API**
app.post("/signup", async (req, res) => {
    const { username, name, emailid, password, repassword, agreeTerms } = req.body;

    if (!agreeTerms) return res.json({ error: "You must agree to the terms!" });
    if (password !== repassword) return res.json({ error: "Passwords do not match!" });

    const existingUser = await User.findOne({ $or: [{ emailid }, { username }] });
    if (existingUser) return res.json({ error: "Username or Email already registered!" });

    const hashedPassword = await bcrypt.hash(password, 10);

    const newUser = new User({ username, name, emailid, password: hashedPassword });
    await newUser.save();

    res.json({ success: "User registered successfully!" });
});

// **LOGIN API**
app.post("/login", async (req, res) => {
    const { username, password } = req.body;

    const user = await User.findOne({ username });
    if (!user) return res.json({ error: "User not found!" });

    const validPassword = await bcrypt.compare(password, user.password);
    if (!validPassword) return res.json({ error: "Incorrect password!" });

    const token = jwt.sign({ id: user._id }, "secretkey", { expiresIn: "1h" });
    res.json({ success: "Login successful!", token });
});

app.listen(5000, () => console.log("Server running on port 5000"));
