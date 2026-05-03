<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get a Quote | ERP17</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #1f2937; }
        .container { max-width: 900px; margin: 32px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; }
        h1 { margin: 0 0 8px; }
        p { color: #475569; line-height: 1.6; }
        .grid { display: grid; gap: 14px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; }
        .checkboxes { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
        .checkboxes label { font-weight: 500; display: flex; align-items: center; gap: 8px; margin: 0; }
        .btn { padding: 10px 14px; border: 0; border-radius: 8px; background: #2563eb; color: #fff; cursor: pointer; font-weight: 600; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
        .flash { background: #dcfce7; color: #166534; border: 1px solid #86efac; border-radius: 8px; padding: 10px; margin-bottom: 14px; }
        @media (max-width: 768px) {
            .grid-2, .checkboxes { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Custom ERP Pricing for Your Business</h1>
            <p>
                At ERP17, we understand that every enterprise has its own challenges and goals. Share your requirements
                and we will prepare a custom ERP plan built for your operations.
            </p>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('quote.store') }}" class="grid">
                @csrf
                <div class="grid grid-2">
                    <div>
                        <label for="name">Name</label>
                        <input id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                        @error('name') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="designation">Designation</label>
                        <input id="designation" name="designation" value="{{ old('designation') }}" placeholder="Owner">
                        @error('designation') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="company_name">Company Name</label>
                        <input id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Enter company name" required>
                        @error('company_name') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="employee_count">Approx. Number of Employees</label>
                        <input id="employee_count" name="employee_count" type="number" min="1" value="{{ old('employee_count') }}" placeholder="e.g. 120">
                        @error('employee_count') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label>Modules Needed</label>
                    <div class="checkboxes">
                        @php
                            $modules = [
                                'full_erp' => 'Full ERP',
                                'hrm' => 'HRM',
                                'crm' => 'CRM',
                                'pos' => 'POS',
                                'ecommerce' => 'ECommerce',
                                'accounts' => 'Accounts',
                            ];
                        @endphp
                        @foreach ($modules as $value => $label)
                            <label>
                                <input
                                    type="checkbox"
                                    name="modules_needed[]"
                                    value="{{ $value }}"
                                    @checked(in_array($value, old('modules_needed', []), true))
                                >
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <p style="margin-top: 6px; font-size: 13px;">You can select multiple modules.</p>
                    @error('modules_needed') <div class="error">{{ $message }}</div> @enderror
                    @error('modules_needed.*') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-2">
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@company.com" required>
                        @error('email') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="mobile_no">Mobile No.</label>
                        <input id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="+880..." required>
                        @error('mobile_no') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="address">Address</label>
                        <input id="address" name="address" value="{{ old('address') }}" placeholder="Enter address">
                        @error('address') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" placeholder="Share your requirements...">{{ old('description') }}</textarea>
                    @error('description') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <button type="submit" class="btn">Get a Quote</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
