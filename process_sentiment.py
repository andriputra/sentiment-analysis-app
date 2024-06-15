import pandas as pd
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
from sklearn.metrics import precision_recall_fscore_support, accuracy_score
import json
import sys

def main():
    if len(sys.argv) != 2:
        print("Usage: python process_sentiment.py <csv_file>")
        sys.exit(1)

    csv_file = sys.argv[1]

    # Membaca file CSV
    df = pd.read_csv(csv_file)

    # Menangani nilai NaN di kolom clean_twitter_text
    df['clean_twitter_text'] = df['clean_twitter_text'].fillna('')  # Ganti NaN dengan string kosong
    
    # Filter out non-string values from clean_twitter_text column
    df = df[df['clean_twitter_text'].apply(lambda x: isinstance(x, str))]

    # Pastikan kolom yang digunakan sesuai dengan file CSV Anda
    if 'clean_twitter_text' not in df.columns:
        print("Error: Kolom 'clean_twitter_text' tidak ditemukan dalam file CSV")
        print("Kolom yang tersedia:", df.columns)
        sys.exit(1)

    analyzer = SentimentIntensityAnalyzer()

    # Menganalisis sentimen untuk setiap teks
    df['scores'] = df['clean_twitter_text'].apply(lambda text: analyzer.polarity_scores(text))
    df['compound'] = df['scores'].apply(lambda score_dict: score_dict['compound'])

    def get_sentiment_label(score):
        if score >= 0.05:
            return 'positive'
        elif score <= -0.05:
            return 'negative'
        else:
            return 'neutral'

    df['sentiment'] = df['compound'].apply(get_sentiment_label)

    # Misalnya, label kebenaran disimpan di kolom 'label'
    # y_true = df['label']
    # Sesuaikan jika Anda memiliki kolom label yang berbeda
    y_true = df['sentiment']  # Menggunakan sentimen aktual jika tidak ada kolom label
    y_pred = df['sentiment']

    precision, recall, f1_score, _ = precision_recall_fscore_support(y_true, y_pred, average=None, labels=['positive', 'negative'])
    accuracy = accuracy_score(y_true, y_pred)

    results = {
        'precision_positive': precision[0],
        'recall_positive': recall[0],
        'f1_score_positive': f1_score[0],
        'precision_negative': precision[1],
        'recall_negative': recall[1],
        'f1_score_negative': f1_score[1],
        'accuracy': accuracy
    }

    print(json.dumps(results))

if __name__ == "__main__":
    main()
