import pandas as pd
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import json
import sys

def main():
    try:
        if len(sys.argv) != 2:
            print("Usage: python process_sentiment.py <csv_file>")
            sys.exit(1)

        csv_file = sys.argv[1]
        nrows_to_read = 4825  # Jumlah baris yang ingin dibaca

        analyzer = SentimentIntensityAnalyzer()

        # Fungsi untuk mendapatkan label sentimen
        def get_sentiment_label(score):
            if score >= 0.05:
                return 'positive'
            elif score <= -0.05:
                return 'negative'
            else:
                return 'neutral'

        # Membaca hanya sejumlah baris tertentu dari file CSV
        df = pd.read_csv(csv_file, nrows=nrows_to_read)

        # Pastikan kolom yang digunakan sesuai dengan file CSV Anda
        if 'tweet_english' not in df.columns:
            raise ValueError("Kolom 'tweet_english' tidak ditemukan dalam file CSV")

        # Menganalisis sentimen untuk setiap teks
        df['scores'] = df['tweet_english'].apply(lambda text: analyzer.polarity_scores(str(text))['compound'])

        # Menentukan sentimen berdasarkan nilai compound
        df['sentiment'] = df['scores'].apply(get_sentiment_label)

        output_csv_file = csv_file.replace('.csv', '_with_sentiment.csv')
        df.to_csv(output_csv_file, index=False)

        # Hitung jumlah sentimen positif, negatif, dan netral
        sentiment_counts = df['sentiment'].value_counts()

        # Hitung total keseluruhan
        total_tweets = len(df)

        # Format hasil sesuai dengan permintaan
        results = {
            'overall': {
                'pos': {
                    'count': int(sentiment_counts.get('positive', 0))
                },
                'neg': {
                    'count': int(sentiment_counts.get('negative', 0))
                },
                'neutral': {
                    'count': int(sentiment_counts.get('neutral', 0))
                },
                'total': total_tweets
            }
        }

        print(json.dumps(results, indent=4))

    except Exception as e:
        print(f"Error: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    main()
