# %%
import pandas as pd
import plotly.express as px

# %%
df=pd.read_csv("smoking_drinking_dataset.csv")

# %%
df=df.dropna()

# %%
df=pd.get_dummies(df)
df=df.drop(columns=['DRK_YN_N'])
df['sight_hear_age']= ((abs(df['sight_left']-1)+	abs(df['sight_right']-1)+abs(df['hear_left']-1)+abs(df['hear_right']-1))+1)*df['age']
df['bmi']=df['height']/df['weight']

# %%


# %%
import seaborn as sns
import matplotlib.pyplot as plt

plt.figure(figsize=(20,20))
sns.heatmap(df.corr(), annot=True, cmap='coolwarm', fmt='.2f')
plt.title('Correlation heatmap')
plt.show()

# %%
feature_name=[
    'age',
    'height',
    'weight',
    'bmi',
    'waistline',
    'sight_hear_age',
    # 'sight_left',
    # 'sight_right',
    # 'hear_left',
    # 'hear_right',
    'SBP',
    'DBP',
    'BLDS',
    #'tot_chole',
    'HDL_chole',
    #'LDL_chole',
    'triglyceride',
    'hemoglobin',
    #'urine_protein',
    #'serum_creatinine',
    'SGOT_AST',
    'SGOT_ALT',
    'gamma_GTP',
    'SMK_stat_type_cd',
    #'sex_Female',
    'sex_Male'
    ]
plt.figure(figsize=(20,20))
sns.heatmap(df[feature_name+['DRK_YN_Y']].corr(), annot=True, cmap='coolwarm', fmt='.2f')
plt.title('Correlation heatmap')
plt.show()

# %%
fig = px.scatter(x=df['bmi'], y=df['hemoglobin'])
fig.show()

# %%
fig = px.histogram(df, x="sex_Male", category_orders=dict(sex=["Male", "Female"]))
fig.show()

# %%
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

# Split the data into independent and dependent variables
X = df[feature_name]
y = df['DRK_YN_Y']

# Split the dataset into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=83)

# %% [markdown]
# #Logistic Regression

# %%


# Create and train the linear regression model
model = LogisticRegression(max_iter=10)
model.fit(X_train, y_train)
# Make predictions
y_pred = model.predict(X_test)

# Evaluate the model
#print(f"Mean Squared Error: {mse}")
#Other metrics
accuracy = accuracy_score(y_test, y_pred)
precision = precision_score(y_test, y_pred)
recall = recall_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred)

print(f"Accuracy: {accuracy}")
print(f"Precision: {precision}")
print(f"Recall: {recall}")
print(f"F1 Score: {f1}")

# %% [markdown]
# #Decision Tree

# %%
#import graphviz
from sklearn import tree
#import matplotlib.plotly as plt

clf = tree.DecisionTreeClassifier(max_depth=3,random_state=42)
clf = clf.fit(X_train, y_train)
#model = tree.plot_tree(clf)
y_pred = clf.predict(X_test)

accuracy = accuracy_score(y_test, y_pred)
precision = precision_score(y_test, y_pred)
recall = recall_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred)

print(f"Accuracy: {accuracy}")
print(f"Precision: {precision}")
print(f"Recall: {recall}")
print(f"F1 Score: {f1}")

# %%
import dtreeviz
viz = dtreeviz.model(clf,
               X_train,
               y_train,
               target_name='class',
               feature_names=feature_name,
               class_names=['Drinker','Non-Drinker'])
v=viz.view()
v.save('dtree3.svg')

# %%


# %% [markdown]
# # AdaBoostClassifier

# %%
from sklearn import tree
from sklearn.ensemble import AdaBoostClassifier

#import matplotlib.plotly as plt

clf = AdaBoostClassifier(tree.DecisionTreeClassifier(max_depth=5))
clf = clf.fit(X_train, y_train)
#model = tree.plot_tree(clf)
y_pred = clf.predict(X_test)

accuracy = accuracy_score(y_test, y_pred)
precision = precision_score(y_test, y_pred)
recall = recall_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred)

print(f"Accuracy: {accuracy}")
print(f"Precision: {precision}")
print(f"Recall: {recall}")
print(f"F1 Score: {f1}")

# %%
import dtreeviz # remember to load the package

viz = dtreeviz.model(regr,
               X_train,
               y_train,
               target_name='class',
               feature_names=feature_name,
               class_names=['Drinker','Non-Drinker'])
v = viz.view()     # render as SVG into internal object
v.show()                 # pop up window
v.save("dtree2.svg")  # optionally save as svg

# %% [markdown]
# # Random Forest

# %%
from sklearn.ensemble import RandomForestClassifier
from sklearn.datasets import make_classification
clf = RandomForestClassifier(max_depth=5)
clf.fit(X_train, y_train)
y_pred = clf.predict(X_test)

accuracy = accuracy_score(y_test, y_pred)
precision = precision_score(y_test, y_pred)
recall = recall_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred)

print(f"Accuracy: {accuracy}")
print(f"Precision: {precision}")
print(f"Recall: {recall}")
print(f"F1 Score: {f1}")

# %%
from sklearn.datasets import make_circles, make_classification, make_moons
from sklearn.discriminant_analysis import QuadraticDiscriminantAnalysis
from sklearn.ensemble import AdaBoostClassifier, RandomForestClassifier
from sklearn.gaussian_process import GaussianProcessClassifier
from sklearn.gaussian_process.kernels import RBF
from sklearn.inspection import DecisionBoundaryDisplay
from sklearn.model_selection import train_test_split
from sklearn.naive_bayes import GaussianNB
from sklearn.neighbors import KNeighborsClassifier
from sklearn.neural_network import MLPClassifier
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.svm import SVC
from sklearn.tree import DecisionTreeClassifier
import xgboost as xgb
from xgboost import XGBClassifier
from xgboost import plot_importance

names = [
    #"Nearest Neighbors",
    #"Linear SVM",
    #"RBF SVM",
    "Logistic Regression",
     "Naive Bayes",
    #"Gaussian Process",
    "Decision Tree",
    "Random Forest",
    "Neural Net",
    "AdaBoost",
    "QDA",
    "XGBoost",
    "XGBoost-Tuned"
]

classifiers = [
    #KNeighborsClassifier(3)
   # SVC(kernel="linear", C=0.025, random_state=42)
    #,SVC(gamma=2, C=1, random_state=42)
    LogisticRegression(),
    GaussianNB(),
    #GaussianProcessClassifier(1.0 * RBF(1.0), random_state=42)
    DecisionTreeClassifier(max_depth=5, random_state=42)
    ,RandomForestClassifier(max_depth=5, n_estimators=10, max_features=10, random_state=42)
    ,MLPClassifier(alpha=0.01, max_iter=1000, random_state=42)
    ,AdaBoostClassifier(random_state=42)
    ,QuadraticDiscriminantAnalysis()
    ,XGBClassifier(booster='gbtree', eval_metric='logloss',random_state=42)
    ,XGBClassifier(learning_rate=1, max_depth=5, n_estimators=100, subsample=0.9, random_state=42,objective='binary:logistic')
]

# %%
for name, clf in zip(names, classifiers):
  clf.fit(X_train, y_train)
  y_pred = clf.predict(X_test)
  accuracy = accuracy_score(y_test, y_pred)
  precision = precision_score(y_test, y_pred)
  recall = recall_score(y_test, y_pred)
  f1 = f1_score(y_test, y_pred)
  print(name, accuracy,precision)
  importance = dict(zip(X_train.columns, model.feature_importances_))
  print(importance)


