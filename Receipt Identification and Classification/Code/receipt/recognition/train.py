import pandas as pd
import numpy as np
from keras.models import Sequential
from keras.layers import Dense, Dropout, Embedding, Conv1D, MaxPooling1D, GlobalAveragePooling1D
from keras.models import load_model

data = pd.read_csv('C:/xampp3/htdocs/receipt/recognition/data.tsv', delimiter='\t', header=0)
categories = {1: 'foods', 2: 'electronics', 3: 'clothes',4: 'travel'}

num_examples = len(data['product'])
num_categories = len(categories)
max_example_len = 20

file = open("C:/xampp3/htdocs/receipt/recognition/bi_grams5.txt", 'rt')
list = [x.strip('\n') for x in file.readlines()]

bigrams_indices = dict((bi, i) for i, bi in enumerate(list))
indices_bigrams = dict((i, bi) for i, bi in enumerate(list))


def encode(string):
    string = '@' + str(string) + '@'
    zipped = zip(*[string[i:] for i in range(2)])
    pairs=[x for x in zipped]
    result = np.zeros((max_example_len),dtype=np.int64)
    for i,pair in enumerate(pairs):
        if i < max_example_len:
            result[i] = bigrams_indices[pair[0]+pair[1]]
    return result


X = []
for i, product in enumerate(data['product']):
    X.append(encode(product))

X = np.array(X, dtype=np.int64)
print(X)

Y = np.zeros((num_examples, num_categories), dtype=np.float64)
print(Y)
for i, category in enumerate(data['category']):
    Y[i, category-1] = 1
    print(Y)

ids = np.arange(num_examples)
np.random.shuffle(ids)

X = X[ids]
Y = Y[ids]

x_train = X[:130]
x_test = X[130:]

y_train = Y[:130]
y_test = Y[130:]


model = Sequential()

model.add(Embedding(len(list), output_dim=max_example_len))
model.add(Conv1D(64, 3, activation='relu'))
model.add(Conv1D(64, 3, activation='relu'))
model.add(MaxPooling1D(3))
model.add(Conv1D(128, 3, activation='relu'))
model.add(Conv1D(128, 3, activation='relu'))
model.add(GlobalAveragePooling1D())
model.add(Dropout(0.5))
model.add(Dense(32))
model.add(Dense(4, activation='sigmoid'))

model.summary()

model.compile(loss='categorical_crossentropy', optimizer='adam', metrics=['accuracy'])
model.fit(x_train, y_train, validation_data=(x_test, y_test), batch_size=1,
          epochs=10, shuffle=True)
# from sklearn.metrics import f1_score, precision_score, recall_score, confusion_matrix
# y_pred1 = model.predict(x_test)
# y_pred = np.argmax(y_pred1, axis=1)

# # Print f1, precision, and recall scores
# print(precision_score(y_test, y_pred , average="macro"))
# print(recall_score(y_test, y_pred , average="macro"))
# print(f1_score(y_test, y_pred , average="macro"))
result = model.predict(x_test[10][np.newaxis])
print("\nprediction = {}, y={}".format(result, y_test[10]))

result = model.predict(x_test[13][np.newaxis])
print("\nprediction = {}, y={}".format(result, y_test[13]))

result = model.predict(x_test[10][np.newaxis])
print("\nprediction = {}, y={}".format(result, y_test[10]))

model.save('C:/xampp3/htdocs/receipt/recognition/trained_model')


model = load_model('trained_model')

result=model.predict(encode('Vh1 Supersonic 1')[np.newaxis])
print(result)