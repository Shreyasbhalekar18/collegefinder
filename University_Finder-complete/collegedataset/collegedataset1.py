import pandas as pd

# Load the dataset
file_path = "updated_colleges_with_cities.xlsx"
df = pd.read_excel(file_path)

# Example: Delete specific entries in the 'City' column where the city is 'Unknown'
df['City'] = df['City'].replace('Unknown', '-')

# Save the updated dataset
df.to_excel('updated_file.xlsx', index=False)
