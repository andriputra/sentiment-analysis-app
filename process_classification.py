import sys
import pandas as pd
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import classification_report, accuracy_score
import json

def main():
    if len(sys.argv) != 2:
        print("Usage: python process_classification.py <input_csv_file>")
        return

    input_csv_file = sys.argv[1]

    # Baca CSV file
    try:
        df = pd.read_csv(input_csv_file)
    except Exception as e:
        print(f"Error reading CSV file: {str(e)}")
        return

    # Batasi data yang diproses sebanyak 4825
    df = df.head(4825)

    # Ambil kolom teks dan sentiment dari CSV
    try:
        texts = df['clean_twitter_text'].astype(str).tolist()
        labels = df['sentiment'].tolist()  # Pastikan ada kolom 'sentiment' yang berisi label kelas
    except KeyError as e:
        print(f"Error: {str(e)} column not found in the CSV file.")
        return

    # Menggunakan Bag of Words (BoW) untuk ekstraksi fitur
    vectorizer = CountVectorizer()
    X = vectorizer.fit_transform(texts)

    # Bagi data menjadi set pelatihan dan pengujian
    X_train, X_test, y_train, y_test = train_test_split(X, labels, test_size=0.2, random_state=42)

    # Buat dan latih model Logistic Regression
    model = LogisticRegression(max_iter=1000)
    model.fit(X_train, y_train)

    # Prediksi pada data uji
    y_pred = model.predict(X_test)

    # Evaluasi model
    accuracy = accuracy_score(y_test, y_pred)
    report = classification_report(y_test, y_pred, output_dict=True)

    results = {
        'accuracy': accuracy,
        'report': report
    }

    # Print hasil sebagai JSON
    print(json.dumps(results))

if __name__ == "__main__":
    main()
