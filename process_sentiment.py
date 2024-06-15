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

    # Pastikan kolom yang digunakan sesuai dengan file CSV Anda
    if 'clean_twitter_text' not in df.columns:
        print("Error: Kolom 'clean_twitter_text' tidak ditemukan dalam file CSV")
        print("Kolom yang tersedia:", df.columns)
        sys.exit(1)

    analyzer = SentimentIntensityAnalyzer()

    # Menganalisis sentimen untuk setiap teks
    df['scores'] = df['clean_twitter_text'].apply(lambda text: analyzer.polarity_scores(text) if isinstance(text, str) else {})
    df['compound'] = df['scores'].apply(lambda score_dict: score_dict.get('compound', 0))

    def get_sentiment_label(score):
        if score >= 0.05:
            return 'positive'
        elif score <= -0.05:
            return 'negative'
        else:
            return 'neutral'

    df['sentiment'] = df['compound'].apply(get_sentiment_label)

    # Menghitung metrik
    y_true = df['sentiment']  # Menggunakan sentimen aktual jika tidak ada kolom label
    y_pred = df['sentiment']

    precision, recall, f1_score, _ = precision_recall_fscore_support(y_true, y_pred, average=None, labels=['positive', 'negative'])
    accuracy = accuracy_score(y_true, y_pred)

    results = {
        'overall': {
            'pos': {
                'precision': precision[0],
                'recall': recall[0],
                'f1': f1_score[0]
            },
            'neg': {
                'precision': precision[1],
                'recall': recall[1],
                'f1': f1_score[1]
            },
            'accuracy': accuracy
        }
    }

    print(json.dumps(results))

if __name__ == "__main__":
    main()
