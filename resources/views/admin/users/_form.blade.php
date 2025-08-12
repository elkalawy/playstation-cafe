{{-- محتوى ملف: _form.blade.php --}}
<div>
    <x-input-label for="name" :value="__('الاسم')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="email" :value="__('البريد الإلكتروني')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="role" :value="__('الصلاحية')" />
    <select name="role" id="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="employee" @selected(old('role', $user->role ?? '') == 'employee')>موظف</option>
        <option value="admin" @selected(old('role', $user->role ?? '') == 'admin')>مدير</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="password" :value="__('كلمة المرور (اتركها فارغة لعدم التغيير)')" />
    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
</div>