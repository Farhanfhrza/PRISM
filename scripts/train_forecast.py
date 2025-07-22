import pandas as pd
from prophet import Prophet
from datetime import datetime
import os

# Path input dan output
input_path = "storage/app/ml/demand_data.csv"
output_path = "public/forecasts/demand_forecast.csv"

# Cek apakah file input ada
if not os.path.exists(input_path):
    raise FileNotFoundError(f"❌ File data tidak ditemukan: {input_path}")

print(f"Membaca data dari: {input_path}")
df = pd.read_csv(input_path)

# Parsing tanggal
df['date'] = pd.to_datetime(df['date'], errors='coerce')
df = df.dropna(subset=['date'])
df['year_month'] = df['date'].dt.to_period('M').dt.to_timestamp()

# Agregasi per bulan
grouped = df.groupby(['year_month', 'stationery_name', 'division'])['amount'].sum().reset_index()
print(f"Jumlah kombinasi barang-divisi: {grouped.groupby(['stationery_name', 'division']).ngroups}")

results = []

for (item, div), subset in grouped.groupby(['stationery_name', 'division']):
    print(f"Melatih model: {item} - {div} ({len(subset)} baris)")

    df_prophet = subset.rename(columns={"year_month": "ds", "amount": "y"})

    if len(df_prophet) < 6:
        print(f"Data kurang dari 6 baris, lewati: {item} - {div}")
        continue

    try:
        model = Prophet()
        model.fit(df_prophet)

        future = model.make_future_dataframe(periods=6, freq='M')
        forecast = model.predict(future)

        # Filter hasil masa depan
        forecast = forecast[forecast['ds'].dt.date > datetime.today().date()]

        if not forecast.empty:
            forecast['stationery_name'] = item
            forecast['division'] = div
            results.append(
                forecast[['ds', 'stationery_name', 'division', 'yhat', 'yhat_lower', 'yhat_upper']]
            )
    except Exception as e:
        print(f"Error pada {item} - {div}: {e}")

if results:
    all_forecasts = pd.concat(results)

    print(f"Simpan hasil prediksi ke: {output_path}")
    all_forecasts.to_csv(output_path, index=False)
    print("Prediksi disimpan sukses.")
else:
    print("Tidak ada hasil prediksi yang bisa disimpan.")
