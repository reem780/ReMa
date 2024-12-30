-- Create the database
CREATE DATABASE IF NOT EXISTS NutritionDB;

-- Use the database
USE NutritionDB;

-- Users table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Role ENUM('admin', 'user') DEFAULT 'user',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Recipes table
CREATE TABLE Recipes (
    RecipeID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Title VARCHAR(100) NOT NULL,
    Description TEXT,
    Calories INT,
    Protein FLOAT,
    Carbs FLOAT,
    Fats FLOAT,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

-- Ingredients table
CREATE TABLE Ingredients (
    IngredientID INT AUTO_INCREMENT PRIMARY KEY,
    RecipeID INT NOT NULL,
    Name VARCHAR(100) NOT NULL,
    Quantity FLOAT NOT NULL,
    Unit VARCHAR(20) NOT NULL,
    FOREIGN KEY (RecipeID) REFERENCES Recipes(RecipeID) ON DELETE CASCADE
);

-- Meal Plans table
CREATE TABLE MealPlans (
    MealPlanID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Title VARCHAR(100) NOT NULL,
    Description TEXT,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

-- MealPlanRecipes table (many-to-many relationship between meal plans and recipes)
CREATE TABLE MealPlanRecipes (
    MealPlanID INT NOT NULL,
    RecipeID INT NOT NULL,
    FOREIGN KEY (MealPlanID) REFERENCES MealPlans(MealPlanID) ON DELETE CASCADE,
    FOREIGN KEY (RecipeID) REFERENCES Recipes(RecipeID) ON DELETE CASCADE,
    PRIMARY KEY (MealPlanID, RecipeID)
);

-- Nutritional Info table
CREATE TABLE NutritionalInfo (
    InfoID INT AUTO_INCREMENT PRIMARY KEY,
    RecipeID INT NOT NULL,
    ServingSize FLOAT NOT NULL,
    Calories INT NOT NULL,
    Protein FLOAT NOT NULL,
    Carbs FLOAT NOT NULL,
    Fats FLOAT NOT NULL,
    FOREIGN KEY (RecipeID) REFERENCES Recipes(RecipeID) ON DELETE CASCADE
);
