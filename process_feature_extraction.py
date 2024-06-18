import sys
import pandas as pd
from sklearn.feature_extraction.text import CountVectorizer
import json

def main():
    if len(sys.argv) != 2:
        print("Usage: python process_feature_extraction.py <input_csv_file>")
        return

    input_csv_file = sys.argv[1]

    # Baca CSV file
    try:
        df = pd.read_csv(input_csv_file)
    except Exception as e:
        print(f"Error reading CSV file: {str(e)}")
        return

    # Batasi jumlah data yang diproses sebanyak 4825 baris
    df = df.head(4825)

    # Ambil kolom teks (tokenize) dan label dari CSV
    try:
        texts = df['tweet_english'].astype(str).tolist()  # Menggunakan kolom 'tokenize'
        labels = df['sentiment'].tolist() 
    except KeyError as e:
        print(f"Error: {str(e)} column not found in the CSV file.")
        return

    # Contoh menggunakan Bag of Words (BoW) untuk ekstraksi fitur
    vectorizer = CountVectorizer()
    X = vectorizer.fit_transform(texts)

    # Ubah hasil ekstraksi menjadi format JSON untuk dikirimkan ke PHP
    features = X.toarray().tolist()

    results = {
        # 'features': features,
        # 'sentiment': labels,
        'overall': {
            'num_samples': len(texts),
            'num_features': len(vectorizer.get_feature_names_out())
        }
    }

    # Print hasil sebagai JSON
    print(json.dumps(results))

if __name__ == "__main__":
    main()
